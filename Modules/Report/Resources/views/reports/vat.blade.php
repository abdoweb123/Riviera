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
                                <li class="active">@lang('trans.VAT')</li>
                            </ol>
                        </div>
                    </div>

                    <div class="panel">
                        <div class="panel-body">
                            <form class="row" action="{{ route('admin.reports.vat') }}">
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
                            <div class="table-responsive">
                                <table class="table table-striped" id="custom_tbl_dt">
                                    <thead>
                                    <tr>
                                        <th style="text-align:center;">@lang('trans.amount')</th>
                                        <th style="text-align:center;">@lang('trans.VatAmount')</th>
                                        <th style="text-align:center;">@lang('trans.VatAmountPercentage')</th>
                                        <th style="text-align:center;">@lang('trans.NoVatAmount')</th>
                                        <th style="text-align:center;">@lang('trans.NoVatAmountPercentage')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($vat) > 0)
                                        <tr>
                                            <td style="text-align:center;">{{ $vat['amount'] }}</td>
                                            <td style="text-align:center;">{{ $vat['VatAmount'] }}</td>
                                            <td style="text-align:center;">{{ $vat['VatAmountPercentage'] }}</td>
                                            <td style="text-align:center;">{{ $vat['NoVatAmount'] }}</td>
                                            <td style="text-align:center;">{{ $vat['NoVatAmountPercentage'] }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="10" style="text-align: center!important;">@lang('trans.noElements')</td>
                                        </tr>
                                    @endif
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
