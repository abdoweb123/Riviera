@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.countries'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.countries.create') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input type="text" name="title_ar" id="title_ar" class="form-control" required value="{{ old('title_ar') }}">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input type="text" name="title_en" id="title_en" class="form-control" required value="{{ old('title_en') }}">
        </div>
        <div class="col-md-6">
            <label for="currancy_code_ar">@lang('trans.currancy_code_ar')</label>
            <input type="text" name="currancy_code_ar" id="currancy_code_ar" class="form-control" required value="{{ old('currancy_code_ar') }}">
        </div>
        <div class="col-md-6">
            <label for="currancy_code_en">@lang('trans.currancy_code_en')</label>
            <input type="text" name="currancy_code_en" id="currancy_code_en" class="form-control" required value="{{ old('currancy_code_en') }}">
        </div>
        <div class="col-md-6">
            <label for="currancy_value">@lang('trans.currancy_value')</label>
            <input type="text" name="currancy_value" id="currancy_value" class="form-control" required value="{{ old('currancy_value') }}">
        </div>
        <div class="col-md-6">
            <label for="phone_code">@lang('trans.phone_code')</label>
            <input type="text" name="phone_code" id="phone_code" class="form-control" required value="{{ old('phone_code') }}">
        </div>
        <div class="col-md-6">
            <label for="phone_length">@lang('trans.phone_length')</label>
            <input type="text" name="length" id="phone_length" class="form-control" required value="{{ old('length') }}">
        </div>
        <div class="col-md-6">
            <label for="decimals">@lang('trans.decimals')</label>
            <input type="text" name="decimals" id="decimals" class="form-control" required value="{{ old('decimals') }}">
        </div>
        <div class="col-md-6">
            <label for="visibility">@lang('trans.visibility')</label>
            <select class="form-control" required id="visibility" name="status">
                <option {{ old('status') == 1 ? 'selected' : '' }} value="1">@lang('trans.visible')</option>
                <option {{ old('status') == 0 ? 'selected' : '' }} value="0">@lang('trans.hidden')</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="file">@lang("trans.image")</label>
            <label for="file" class="file-input btn btn-block btn-primary btn-file w-100">
                @lang("trans.Browse")
                <input accept="image/*" type="file" type="file" name="image" id="file" value="{{ old('image') }}">
            </label>
        </div>
        <div class="clearfix"></div>
        <div class="col-12 my-4">
            <div class="button-group">
                <button type="submit" class="main-btn btn-hover w-100 text-center">
                    {{ __('Submit') }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
