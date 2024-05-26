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
                                <li class="active">@lang('trans.salesReport')</li>
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
                            @php($net_total = 0)
                            @php($sub_total = 0)
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="ConsutaBPM">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('trans.orderNo')</th>
                                        <th style="text-align:center;">@lang('trans.paymentMethod')</th>
                                        <th style="text-align:center;">@lang('trans.subTotal')</th>
                                        <th style="text-align:center;">@lang('trans.netTotal')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($orders) > 0)
                                        @foreach($orders as $key => $order)
                                            <tr class="gradeX {{ $order['id'] }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="text-align:center;">{{ $order['id'] }}</td>
                                                <td>{{ $order->Payment['title_' . app()->getLocale()] }}</td>
                                                <td>{{ $order->sub_total }} {{  Country()->currancy_code }}</td>
                                                @php($sub_total += $order['sub_total'])
                                                <td>{{ $order['net_total'] }} {{  Country()->currancy_code }}</td>
                                                @php($net_total += $order['net_total'])
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                @lang('trans.subTotal'): {{ $sub_total }} {{  Country()->currancy_code }}
                                            </td>
                                            <td>
                                                @lang('trans.netTotal'): {{ $net_total }} {{  Country()->currancy_code }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="6" style="text-align: center!important;">@lang('trans.noElements')</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                @if (count($orders) > 0)
                                    <div class="col-md-3 form-group" style="padding-top: 26px">
                                        <form class="row" action="{{ route('admin.exportData', ['data' => $orders]) }}" method="GET" style="display:contents">
                                            @csrf
                                            <button class="btn btn-dark">@lang('trans.exportExcel')</button>
                                        </form>
                                    </div>
                                    <div class="col-md-3 form-group" style="padding-top: 26px">
                                        <button onclick="imprimir()" class="btn btn-info ">@lang('trans.exportPDF')</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">   
        function imprimir() {

            newWin = window.open("");
            newWin.document.write('<html>');
            newWin.document.write('<head>');
            newWin.document.write('<title>' + document.title  + '</title>');
            @if(lang('ar'))
            newWin.document.write('<style>body{direction: rtl;} table{width: 100%;text-align: center;}</style>');
            @endif
            newWin.document.write('</head>');
            newWin.document.write('<body >');
            newWin.document.write(document.getElementById("ConsutaBPM").outerHTML);
            newWin.document.write('</body>');
            newWin.document.write('</html>');
            newWin.print();
            newWin.close();
            
        }
    </script>
@endsection
