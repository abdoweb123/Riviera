@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('content')

    <div class="wrapper">
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="page-title">@lang('trans.reports')</h4>
                            <ol class="breadcrumb">
                                <li><a href="">@lang('trans.reports')</a></li>
                                <li class="active">@lang('trans.financialReport')</li>
                            </ol>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-body">
                            <form class="row">

                                <div class="col-md-3 form-group">
                                    <label for="from">@lang('trans.from')</label>
                                    <input type="date" id="from" name="from" class="form-control" value="{{ request('from') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="to">@lang('trans.to')</label>
                                    <input type="date" id="to" name="to" class="form-control" value="{{ request('to') }}">
                                </div>
                                <div class="col-md-3 form-group" style="padding-top: 26px">
                                    <button class="main-btn">@lang('trans.search')</button>
                                </div>
                            </form>
                            @if (count($orders) > 0)
                                <form class="row" action="{{ route('admin.exportData', ['data' => $orders]) }}" method="GET">
                                    @csrf
                                    <div class="col-md-3 form-group" style="padding-top: 26px">
                                        <button class="btn btn-dark">@lang('trans.exportExcel')</button>
                                    </div>
                                </form>
                            @endif
                            @php($total = 0)
                            <h3 class="m-b-10 m-t-40">@lang('trans.orders')</h3>
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="custom_tbl_dt">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('trans.orderNo')</th>
                                        <th style="text-align:center;">@lang('trans.client')</th>
                                        <th style="text-align:center;">@lang('trans.phone')</th>
                                        <th style="text-align:center;">@lang('trans.netTotal')</th>
                                        <th style="text-align:center;">@lang('trans.paymentMethod')</th>
                                        <th style="text-align:center;">@lang('trans.time')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($orders) > 0)
                                        @foreach($orders as $key => $order)
                                            <tr class="gradeX {{ $order['id'] }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align:center;">{{ $order['id'] }}</td>
                                                <td style="text-align:center;">{{ $order->client?->name }}</td>
                                                <td style="text-align:center;">{{ $order->client?->phone }}</td>
                                                <td>{{ $order['net_total'] }} {{  Country()->currancy_code }}</td>
                                                @php($total += $order['net_total'])
                                                <td>{{ $order->Payment['title_' . app()->getLocale()] }}</td>
                                                <td>{{ $order['created_at'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" style="text-align: center!important;">@lang('trans.noElements')</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="9" style="text-align: center!important;">
                                            @lang('trans.netTotal'): {{ $total }} {{  Country()->currancy_code }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
