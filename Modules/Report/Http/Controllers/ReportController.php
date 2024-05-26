<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\BasicController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Client\Entities\Model as Client;
use Modules\Order\Entities\Item;
use Modules\Order\Entities\Model as Order;
use Modules\Report\Exports\AllUsers;
use Modules\Report\Exports\ClientExport;
use Modules\Report\Exports\FinancialExport;
use Modules\Report\Exports\MostSelling;
use Modules\Report\Exports\PaymentExport;
use Modules\Report\Exports\Products;
use Modules\Report\Exports\SalesExport;
use Modules\Transaction\Entities\Model as Transaction;

class ReportController extends BasicController
{
    public function sales()
    {
        $branch_ids = collect();
        $address_ids = collect();

        $orders = Order::whereIn('status', [0, 1, 2, 9])

            ->when(request('from'), function ($query) {
                return $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query) {
                return $query->whereDate('created_at', '<=', request('to'));
            })
            ->get();

        Session::put('reports_data', $orders);
        Session::put('reports_type', 'sales');

        return view('report::reports.sales', compact('orders'));
    }

    public function financial()
    {
        $branch_ids = collect();
        $address_ids = [];

        $orders = Order::whereIn('status', [0, 1, 2, 9])

            ->when(request('from'), function ($query) {
                return $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query) {
                return $query->whereDate('created_at', '<=', request('to'));
            })
            ->get();

        Session::put('reports_data', $orders);
        Session::put('reports_type', 'financial');

        return view('report::reports.financial', compact('orders'));
    }

    public function client()
    {
        $clients = Client::all();
        $client = null;
        if (request('client_id')) {
            $client = Client::find(request('client_id'));
        }
        $client ? Session::put('reports_data', $client->Orders) : '';
        Session::put('reports_type', 'client');

        return view('report::reports.client', compact('client', 'clients'));
    }

    public function payment()
    {
        $reports = Transaction::query()
            ->when(request('from'), function ($query) {
                return $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query) {
                return $query->whereDate('created_at', '<=', request('to'));
            })
            ->get();

        Session::put('reports_data', $reports);
        Session::put('reports_type', 'payment');

        return view('report::reports.payment', compact('reports'));
    }

    public function mostselling()
    {
        $MostSelling = Item::query()
            ->with(['Service' => function ($Service) {
                $Service->select('title_'.lang(), 'id');
            }])
            ->when(request('from'), function ($query) {
                return $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query) {
                return $query->whereDate('created_at', '<=', request('to'));
            })
            ->select('service_id', DB::raw('COUNT(service_id) as count'), DB::raw('DATE(created_at)'))
            ->groupBy('service_id')
            ->orderby('count', 'DESC')
            ->get();

        Session::put('reports_data', $MostSelling);
        Session::put('reports_type', 'mostselling');

        return view('report::reports.mostselling', compact('MostSelling'));
    }

    public function products()
    {
        $products = Item::with(['Service' => function ($Service) {
            $Service->select('title_'.lang(), 'id');
        }])->select('service_id', 'price', DB::raw('id as count'), DB::raw('DATE(created_at)'));

        if (request('sort') == 'price_desc') {
            $products = $products->orderby('price', 'DESC')->get();
        } elseif (request('sort') == 'price_asc') {
            $products = $products->orderby('price', 'ASC')->get();
        } elseif (request('sort') == 'quantity_desc') {
            $products = $products->orderby('count', 'DESC')->get();
        } elseif (request('sort') == 'price_asc') {
            $products = $products->orderby('count', 'ASC')->get();
        } else {
            $products = $products->orderby('count', 'asc')->get();
        }
        Session::put('reports_data', $products);
        Session::put('reports_type', 'products');

        return view('report::reports.products', compact('products'));
    }

    public function vat()
    {
        $Orders = Order::query()
            ->when(request('from'), function ($query) {
                return $query->whereDate('created_at', '>=', request('from'));
            })
            ->when(request('to'), function ($query) {
                return $query->whereDate('created_at', '<=', request('to'));
            });

        $amount = (float) $Orders->sum('net_total');
        $VatAmount = (float) $Orders->where('vat', '>', 0.000)->sum('vat');
        $NoVatAmount = (float) $amount - $VatAmount;
        $vat = [
            'amount' => $amount,

            'VatAmount' => $VatAmount,
            'VatAmountPercentage' => setting('VAT') ? $VatAmount / setting('VAT') : 0,

            'NoVatAmount' => $NoVatAmount,
            'NoVatAmountPercentage' => 0,
        ];
        Session::put('reports_data', $vat);
        Session::put('reports_type', 'vat');

        return view('report::reports.vat', compact('vat'));
    }

    public function exportData()
    {
        if (Session::get('reports_type') == 'sales') {
            $export = new SalesExport([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'financial') {
            $export = new FinancialExport([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'client') {
            $export = new ClientExport([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'payment') {
            $export = new PaymentExport([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'clients') {
            $export = new AllUsers([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'mostselling') {
            $export = new MostSelling([Session::get('reports_data')]);
        }
        if (Session::get('reports_type') == 'products') {
            $export = new Products([Session::get('reports_data')]);
        }
        session()->forget('data');
        session()->forget('type');

        return Excel::download($export, 'report- '.now().'.xlsx');
    }
}
