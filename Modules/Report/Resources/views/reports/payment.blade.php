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
                                <li class="active">@lang('trans.paymentReport')</li>
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
                            @if (count($reports) > 0)
                                <form class="row" action="{{ route('admin.exportData') }}"
                                    method="GET">
                                    @csrf
                                    <div class="col-md-3 form-group" style="padding-top: 26px">
                                        <button class="btn btn-dark">@lang('trans.exportExcel')</button>
                                    </div>
                                </form>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-striped" id="custom_tbl_dt">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="text-align:center;">@lang('trans.client')</th>
                                        <th style="text-align:center;">@lang('trans.value')</th>
                                        <th style="text-align:center;">@lang('trans.result')</th>
                                        <th style="text-align:center;">@lang('trans.payment_type')</th>
                                        <th style="text-align:center;">@lang('trans.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($reports) > 0)
                                        @foreach($reports as $key => $report)
                                            <tr class="gradeX {{ $report['id'] }}">
                                                <td style="text-align:center;">{{ $key + 1 }}</td>
                                                <td style="text-align:center;">{{ $report['client']['name'] }}</td>
                                                <td style="text-align:center;">{{ $report['value'] }}</td>
                                                <td style="text-align:center;">{{ $report['result'] }}</td>
                                                <td style="text-align:center;">@lang('trans.' . $report['type'])</td>
                                                <td style="text-align:center;">{{ $report['created_at'] }}</td>
                                            </tr>
                                        @endforeach
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

