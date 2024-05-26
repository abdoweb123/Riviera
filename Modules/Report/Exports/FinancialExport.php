<?php

namespace Modules\Report\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FinancialExport implements FromView
{
    protected $orders;

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('report::exports.FinancialExport', [
            'orders' => $this->orders,
        ]);
    }
}
