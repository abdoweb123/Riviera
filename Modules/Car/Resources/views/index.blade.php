@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.cars'))
@section('content')

<div class="row">
    <div class="my-2 col-6 text-sm-start">
        <a href="{{ route(activeGuard().'.cars.create') }}" class="main-btn">@lang('trans.add_new')</a>
    </div>
    <div class="my-2 col-6 text-sm-end">
        <button type="button" id="DeleteSelected" onclick="DeleteSelected('cars')" class="btn btn-danger" disabled>@lang('trans.Delete_Selected')</button>
    </div>
</div>
<table class="table table-bordered data-table" >
    <thead>
        <tr>
            <th><input type="checkbox" id="ToggleSelectAll" class="main-btn"></th>
            <th>#</th>
            <th>@lang('trans.driver')</th>
            <th>@lang('trans.car_type')</th>
            <th>@lang('trans.title_ar')</th>
            <th>@lang('trans.title_en')</th>
            <th>@lang('trans.status')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr>
            <td>
                <input type="checkbox" class="DTcheckbox" value="{{ $Model->id }}">
            </td>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $Model->driver->name ?? ''}}</td>
            <td>{{ $Model->carType['title_'.lang()] ?? ''}}</td>
            <td>{{ $Model->title_ar }}</td>
            <td>{{ $Model->title_en }}</td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $Model->id }},'cars')" @if ($Model->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.cars.show', $Model) }}"><i class="fa-solid fa-eye"></i></a>
                <form class="formDelete" method="POST" action="{{ route(activeGuard().'.cars.destroy', $Model) }}">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="button" class="btn btn-flat show_confirm" data-toggle="tooltip" title="Delete">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
