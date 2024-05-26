@extends('Admin.Layouts.layout')
@section('pagetitle',__('trans.dashboardTitle'))

@section('content')

    <div class="row">
        <div class="wrapper">
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="">
                        <div class="row">

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.clients.index') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa fa-users h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $clientsCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.clients')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.get_rides','future') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa-solid fa-route h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $futureRidesCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.futureRides')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.get_rides','previous') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa-solid fa-route h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $previousRidesCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.previousRides')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.drivers.index') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa-solid fa-drivers-license h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $driversCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.drivers')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.cars-types.index') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa fa-car h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $carTypesCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.cars_types')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.cars.index') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa fa-car h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $carsCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.cars')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-6 col-lg-3 my-2">
                                <a href="{{ route('admin.complaints.index') }}" class="d-block">
                                    <div class="mb-4 box-shadow text-center border">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <i class="fa-solid fa-person-circle-exclamation h1"></i>
                                                </div>
                                                <div class="col-4">
                                                    <h4 class="card-title pricing-card-title mt-2">{{ $complaintsCount }}</h4>
                                                </div>
                                            </div>
                                            <p class="text-muted">@lang('trans.complaints')</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


           <div class="content-page">
                <h3 class="portlet-title text-dark">@lang('trans.clientsByDay')</h3>
                <div id="clientsByDay" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="content-page">
                <h3 class="portlet-title text-dark">@lang('trans.ridesByDay')</h3>
                <div id="ridesByDay" style="height: 370px; width: 100%;"></div>
            </div>

            <div class="content-page">
                <h3 class="portlet-title text-dark">@lang('trans.ridesByMonth')</h3>
                <div id="ridesByMonth" style="height: 370px; width: 100%;"></div>
            </div>

        </div>
    </div>

@endsection


@section('script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    @if (session()->has('toast_message_admin'))
        <script>
            // Display the toast notification
            // toast('{{ Session::get('toast_message') }}', 'error');
            var toastMessage = '{{ Session::get('toast_message_admin') }}';
            if (toastMessage) {
                Toastify({
                    text: toastMessage,
                    duration: 2500, // 3 seconds
                    close: false,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #52bcbe, #52bcbe)",
                    width: "500px",
                }).showToast();

            }
        </script>
    @endif
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        var chartOrders = new CanvasJS.Chart("ridesByDay", {
            animationEnabled: true,
            theme: "light1",
            data: [{
                type: "line",
                indexLabelFontSize: 16,
                dataPoints: <?php echo json_encode($chartRides, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartOrders.render();

        var chartUsers = new CanvasJS.Chart("clientsByDay", {
            animationEnabled: true,
            theme: "light1",
            data: [{
                type: "line",
                indexLabelFontSize: 16,
                dataPoints: <?php echo json_encode($chartClients, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartUsers.render();

        var chartChanges = new CanvasJS.Chart("ridesByMonth", {
            animationEnabled: true,
            theme: "light1",
            data: [{
                type: "line",
                indexLabelFontSize: 16,
                dataPoints: <?php echo json_encode($chartChanges, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chartChanges.render();
    </script>
@stop

@section('style')
    <style>
        .logoContainer {
            position: relative;
            background: lightgrey;
        }

        .logoContainer {
            position: relative;
        }

        .vertical-center {
            margin: 0;
            position: relative;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .progressText {
        {{ auth()->user()->theme == 1 ? 'fill: black;' : 'fill: white;' }}
}

        .dataTables_wrapper {
            overflow-x: scroll;
        }

        .stats {
            margin: 5px;
        }

        .stats .col {
        //border: 1px solid red;
            margin: 0;
            padding: 3px;
        }

        .statContainer {
            margin: 5px;
            width: 100%;
            font-size: 13px;
            border-radius: 3px;
            background-color: #fff !important;
            padding: 0;
            overflow: hidden;
        }

        .statContainer .title {
            padding: 5px 10px;
            color: #fff !important;
        }

        .statContainer.red .title {
            background-color: red !important;
        }

        .statContainer.red .status {
            color: red !important;
        }

        .statContainer.blue .title {
            background-color: #2d72c0 !important;
        }

        .statContainer.blue .status {
            color: #2d72c0 !important;
        }

        .statContainer.yellow .title {
            background-color: #f3a254 !important;
        }

        .statContainer.yellow .status {
            color: #f3a254 !important;
        }

        .statContainer.fountainBlue .title {
            background-color: #6abebf !important;
        }

        .statContainer.fountainBlue .status {
            color: #6abebf !important;
        }

        .statContainer.lightBlue .title {
            background-color: #52a1e5 !important;
        }

        .statContainer.lightBlue .status {
            color: #52a1e5 !important;
        }

        .statContainer.purple .title {
            background-color: #916df6 !important;
        }

        .statContainer.purple .status {
            color: #916df6 !important;
        }

        .statContainer.pink .title {
            background-color: #ef6e85 !important;
        }

        .statContainer.pink .status {
            color: #ef6e85 !important;
        }

        .statContainer.orange .title {
            background-color: #ff7043 !important;
        }

        .statContainer.orange .status {
            color: #ff7043 !important;
        }

        .pi {
            width: 200px;
            height: 200px;
            border: 2px solid green;
            border-radius: 50%;
            position: relative;
            left: 25em;
        }

        #innercircle {
            width: 110px;
            height: 110px;
            border: 1px solid white;
            border-radius: 50%;
            background-color: white !important;
            position: absolute;
            top: 22%;
            left: 22%;
        }

        .flot-chart>ul {
            list-style-type: none;
            position: relative;
            left: -1em;
            bottom: 2em;
        }

        #platform li {
            font-size: 1.3em;
        }

        #platform li:nth-child(1)::before {
            font-size: 2em;
            content: "• ";
            color: red;
            vertical-align: middle;
        }

        #platform li:nth-child(2)::before {
            font-size: 2em;
            content: "• ";
            color: green;
            vertical-align: middle;
        }

        #platform li:nth-child(3)::before {
            font-size: 2em;
            content: "• ";
            color: blue;
            vertical-align: middle;
        }


        #payment li {
            font-size: 1.3em;
        }

        #payment li:nth-child(1)::before {
            font-size: 2em;
            content: "• ";
            color: DarkGray;
            vertical-align: middle;
        }

        #payment li:nth-child(2)::before {
            font-size: 2em;
            content: "• ";
            color: black;
            vertical-align: middle;
        }

        #payment li:nth-child(3)::before {
            font-size: 2em;
            content: "• ";
            color: brown;
            vertical-align: middle;
        }

        .chart {
            width: 100%;
            height: 370px;
            width: 100%;
            display: block;
            margin: auto;
        }

        .numbers {
            color: #fff;
            margin: 0;
            padding: 0;
            width: 50px;
            height: 100%;
            display: inline-block;
            float: left;
            position: relative;
        }

        .numbers li {
            list-style: none;
            height: 150px;
            position: relative;
            bottom: 145px;
        }

        .numbers span {
            font-size: 12px;
            font-weight: 600;
            position: absolute;
            bottom: 0;
            right: -23vw;
            color: #555;
        }

        .bars {
            color: #fff;
            font-size: 12px;
            font-weight: 600;
        /background: #555;/
        background-image: url('./include/squares.jpg');
            background-size: cover;
            background-repeat: no-repeat;

        /margin: 0;/
        /padding: 0;/
        display: inline-block;
        /width: 500px;/
        width: max-content;
            margin: auto;
            height: 370px;
            width: 100%;
        /overflow-x: auto;/
        box-shadow: 0 0 10px 0 #555;
            border-radius: 5px;
        }

        .bars li {
            display: table-cell;
            width: 100px;
            height: 370px;
            width: 100%;
            position: relative;
        }

        .bars span {
            width: 100%;
            position: absolute;
            bottom: -30px;
            left: 3px;
            text-align: center;
            color: #555;
        }

        .bars .bar {
            display: block;
            background: #000;
            width: 50px;
            position: absolute;
            bottom: 0;
            margin-left: 25px;
            text-align: center;
            box-shadow: 0 0 10px 0 rgba(23, 192, 235, 0.5);
            transition: 0.5s;
            transition-property: background, box-shadow;
        }

        .bars .bar:hover {
            background: #55EFC4;
            box-shadow: 0 0 10px 0 rgba(85, 239, 196, 0.5);
            cursor: pointer;
        }

        .bars .bar:before {
            color: #000;
            content: attr(data-percentage) '%';
            position: relative;
            bottom: 20px;
        }
    </style>
@stop