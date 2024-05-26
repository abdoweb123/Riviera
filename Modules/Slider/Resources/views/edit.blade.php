@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.sliders'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.sliders.update',$Model) }}" enctype="multipart/form-data" >
    @csrf
    @method('PUT')
    <div class="text-center">
        <img src="{{ asset($Model->file ?? setting('logo')) }}" class="rounded mx-auto text-center" id="file"  height="200px">
    </div>
    <div class="row">
        {{--
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input id="title_ar" type="text" name="title_ar" placeholder="@lang('trans.title_ar')" class="form-control" value="{{ $Model['title_ar'] }}">
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input id="title_en" type="text" name="title_en" placeholder="@lang('trans.title_en')" class="form-control" value="{{ $Model['title_en'] }}">
        </div>
        <div class="col-md-6">
            <label for="link">@lang('trans.link')</label>
            <input id="link" type="text" name="link" placeholder="@lang('trans.link')" class="form-control" value="{{ $Model['link'] }}">
        </div>
        <div class="col-md-6 col-sm-12">
            <label >@lang('trans.desc_ar')</label>
            <textarea name="desc_ar" placeholder="@lang('trans.desc_ar')" class="form-control mceNoEditor">{!! $Model['desc_ar'] !!}</textarea>
        </div>
        <div class="col-md-6 col-sm-12">
            <label >@lang('trans.desc_en')</label>
            <textarea name="desc_en" placeholder="@lang('trans.desc_en')" class="form-control mceNoEditor">{!! $Model['desc_en'] !!}</textarea>
        </div>
        --}}
        <div class="col-md-6 col-sm-12">
            <label for="file">{{ __('trans.file') }}</label>
            <input class="form-control w-100" id="file" type="file" name="file" onchange="document.getElementById('file').src = window.URL.createObjectURL(this.files[0])">
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
