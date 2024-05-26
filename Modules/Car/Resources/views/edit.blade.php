@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.cars'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.cars.update',$Model) }}" enctype="multipart/form-data" >
    @csrf
    @method('PUT')

    <div class="text-center">
        <img src="{{ asset($Model->image ?? setting('logo')) }}" class="rounded mx-auto text-center"  id="image"  height="200px">
    </div>

    <input type="hidden" name="driver_id" value="{{$driver->id}}">

    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input id="title_ar" type="text" name="title_ar" required placeholder="@lang('trans.title_ar')" class="form-control" value="{{ $Model['title_ar'] }}">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input id="title_en" type="text" name="title_en" required placeholder="@lang('trans.title_en')" class="form-control" value="{{ $Model['title_en'] }}">
        </div>
        <div class="col-md-6">
            <label for="license_no">@lang('trans.license_no')</label>
            <input id="license_no" type="text" name="license_no" placeholder="@lang('trans.license_no')" class="form-control" value="{{ $Model['license_no'] }}">
        </div>
        <div class="col-md-6">
            <label for="color">@lang('trans.color')</label>
            <input id="color" type="text" name="color" placeholder="@lang('trans.color')" class="form-control" value="{{ $Model['color'] }}">
        </div>
        <div class="col-md-6">
            <label for="visibility">@lang('trans.visibility')</label>
            <select class="form-control" required id="visibility" name="status">
                <option {{ $Model['status'] == 1 ? 'selected' : '' }} value="1">@lang('trans.visible')</option>
                <option {{ $Model['status'] == 0 ? 'selected' : '' }} value="0">@lang('trans.hidden')</option>
            </select>
        </div>
        
        <div class="col-12">
            <div class="button-group my-4">
                <button type="submit" class="main-btn btn-hover w-100 text-center">
                    {{ __('trans.Submit') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
