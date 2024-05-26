@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.rating'))
@section('content')
    <div class="row">
        <div class="col-2">
            @lang('trans.client_name')
        </div>
        <div class="col-10">
            {{  $Model->client->name }}
        </div>

        <div class="col-2">
            @lang('trans.driver_name')
        </div>
        <div class="col-10">
            {{ $Model->driver->name }}
        </div>

        <div class="col-2">
            @lang('trans.rating')
        </div>
        <div class="col-10">
            {{ $Model['rating'] }}
        </div>

        <div class="col-2">
            @lang('trans.comment')
        </div>
        <div class="col-10">
            {{ $Model['comment'] }}
        </div>
    </div>

@endsection
