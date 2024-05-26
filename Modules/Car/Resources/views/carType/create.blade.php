@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.cars_types'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.cars-types.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="text-center">
        <img src="" class="rounded mx-auto text-center"  id="image"  height="200px">
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input id="title_ar" type="text" name="title_ar" required placeholder="@lang('trans.title_ar')" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input id="title_en" type="text" name="title_en" required placeholder="@lang('trans.title_en')" class="form-control">
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="image">{{ __('trans.people_number') }}</label>
            <input type="number" min="1" name="number" value="{{ old('number'),1 }}" required placeholder="@lang('trans.people_number')" class="form-control">
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="image">{{ __('trans.image') }}</label>
            <input class="form-control w-100" id="image" type="file" name="image" onchange="document.getElementById('image').src = window.URL.createObjectURL(this.files[0])">
        </div>
        <div class="form-group col-md-6">
            <label for="visibility">@lang('trans.visibility')</label>
            <select class="form-control" required id="visibility" name="status">
                <option selected value="1">@lang('trans.visible')</option>
                <option value="0">@lang('trans.hidden')</option>
            </select>
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="image">{{ __('trans.price') }}</label>
            <input type="number" min="1" name="price" value="{{ old('price'),1 }}" required placeholder="@lang('trans.price_km')" class="form-control">
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