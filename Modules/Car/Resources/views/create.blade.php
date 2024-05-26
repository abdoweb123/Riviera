@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.cars'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.cars.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="text-center">
        <img src="" class="rounded mx-auto text-center"  id="image"  height="200px">
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input id="title_ar" type="text" name="title_ar" placeholder="@lang('trans.title_ar')" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input id="title_en" type="text" name="title_en" placeholder="@lang('trans.title_en')" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="license_no">@lang('trans.license_no')</label>
            <input id="license_no" type="text" name="license_no" placeholder="@lang('trans.license_no')" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="color">@lang('trans.color')</label>
            <input id="color" type="text" name="color" placeholder="@lang('trans.color')" class="form-control">
        </div>
        <div class="form-group col-md-6">
            <label for="visibility">@lang('trans.visibility')</label>
            <select class="form-control" required id="visibility" name="status">
                <option selected value="1">@lang('trans.visible')</option>
                <option value="0">@lang('trans.hidden')</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">@lang('trans.drivers')</label>
            <select class="form-control selectpicker" data-live-search="true" id="driver_id" name="driver_id">
                <option value="" selected disabled hidden>----------</option>
                @foreach ($drivers as $Item)
                    <option value="{{ $Item->id }}">{{ $Item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">@lang('trans.cars_types')</label>
            <select class="form-control selectpicker" data-live-search="true" id="carType_id" name="carType_id">
                <option value="" selected disabled hidden>----------</option>
                @foreach ($car_types as $Item)
                    <option value="{{ $Item->id }}">{{ $Item['title_'.lang()] }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-sm-12 my-4">
                <div class="text-center p-20">
                    <button type="submit" class="main-btn">{{ __('trans.add') }}</button>
                    <button type="reset" class="btn btn-secondary">{{ __('trans.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <style>
        .features_block {
            border: 1px solid #CCC;
            margin: 10px 0px;
            padding: 10px 0px;
        }
    </style>
@endsection



@section('js')
    <script src="https://unpkg.com/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(".selectpicker").selectpicker();
        features_i = 1;
        items_i = 9999;
        $(document).on('click', '#selectAll', function() {
            $('#permissions option').attr("selected", "selected");
            $(".selectpicker").selectpicker('refresh');
        });
    </script>
@endsection