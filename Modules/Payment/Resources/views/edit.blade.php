@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.paymentMethods'))
@section('content')
    <form method="POST" action="{{ route(activeGuard().'.payments.update',$Model) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="text-center">
            <img src="{{ asset($Model->image ?? setting('logo')) }}" class="rounded mx-auto text-center"  id="image" style="max-width: 100%;max-height: 200px"   >
        </div>
        <div class="row">
            <div class=" col-md-6">
                <label for="title_en">@lang('trans.title_en')</label>
                <input id="title_en" type="text" name="title_en" required placeholder="@lang('trans.title_en')" class="form-control" value="{{ $Model['title_en'] }}">
            </div>
            <div class=" col-md-6">
                <label for="title_ar">@lang('trans.title_ar')</label>
                <input id="title_ar" type="text" name="title_ar" required placeholder="@lang('trans.title_ar')" class="form-control" value="{{ $Model['title_ar'] }}">
            </div>

            <div class=" col-md-6">
                <label for="visibility">@lang('trans.visibility')</label>
                <select class="form-control" required id="visibility" name="status">
                    <option {{ $Model['status'] == 0 ? 'selected' : '' }} value="0">@lang('trans.hidden')</option>
                    <option {{ $Model['status'] == 1 ? 'selected' : '' }} value="1">@lang('trans.visible')</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="image">{{ __('trans.image') }}</label>
                <input class="form-control w-100" id="image" type="file" name="image" onchange="document.getElementById('image').src = window.URL.createObjectURL(this.files[0])">
            </div>


           
            <div class="col-12 my-3">
                <div class="button-group my-4 text-center">
                    <button type="submit" class="main-btn btn-hover w-100 text-center">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
