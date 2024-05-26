@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.drivers'))
@section('content')

<div class="row">
    <div class="my-2 col-6 text-sm-start">
        <a href="{{ route(activeGuard().'.drivers.create') }}" class="main-btn">@lang('trans.add_new')</a>
    </div>
</div>
<table class="table table-bordered data-table text-center" >
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.name')</th>
            <th>@lang('trans.image')</th>
            <th>@lang('trans.phone')</th>
            <th>@lang('trans.email')</th>
            <th>@lang('trans.status')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr Role="row" class="odd">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $Model->name }}</td>
            <td><img src="{{asset($Model->image)}}" alt="" style="width:50px; height:50px"></td>
            <td>{{ $Model->phone_code.$Model->phone }}</td>
            <td>{{ $Model->email }}</td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $Model->id }},'drivers')" @if ($Model->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.drivers.car.edit', ['driver'=>$Model]) }}"><i class="fa-solid fa-car"></i></a>
                <a href="{{ route(activeGuard().'.drivers.edit', ['driver'=>$Model]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                <a href="{{ route(activeGuard().'.drivers.show', ['driver'=>$Model]) }}"><i class="fa-solid fa-eye"></i></a>
                <form class="formDelete" method="POST" action="{{ route(activeGuard().'.drivers.destroy', ['driver'=>$Model]) }}">
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
