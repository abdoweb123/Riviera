@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.cars_types'))
@section('content')

<div class="row">
    <div class="my-2 col-6 text-sm-start">
        <a href="{{ route(activeGuard().'.cars-types.create') }}" class="main-btn">@lang('trans.add_new')</a>
    </div>
    <div class="my-2 col-6 text-sm-end">
        <button type="button" id="DeleteSelected" onclick="DeleteSelected('car_types')" class="btn btn-danger" disabled>@lang('trans.Delete_Selected')</button>
    </div>
</div>
<table class="table table-bordered data-table" >
    <thead>
        <tr>
            <th><input type="checkbox" id="ToggleSelectAll" class="main-btn"></th>
            <th>#</th>
            <th>@lang('trans.image')</th>
            <th>@lang('trans.title_ar')</th>
            <th>@lang('trans.title_en')</th>
            <th>@lang('trans.price_km')</th>
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
            <td>
                <img src="{{asset($Model->image ?? '')}}" alt="" style="width: 100px; height:100px;">
            </td>
            <td>{{ $Model->title_ar }}</td>
            <td>{{ $Model->title_en }}</td>
            <td>{{ $Model->price }}</td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $Model->id }},'car_types')" @if ($Model->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.cars-types.show', $Model) }}"><i class="fa-solid fa-eye"></i></a>
                <a href="{{ route(activeGuard().'.cars-types.edit', $Model) }}"><i class="fa-solid fa-edit"></i></a>

                @if(count($Model->cars) < 1)
                <form class="formDelete" method="POST" action="{{ route(activeGuard().'.cars-types.destroy', $Model) }}">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="button" class="btn btn-flat show_confirm" data-toggle="tooltip" title="Delete">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
