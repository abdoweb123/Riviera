@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.rides'))
@section('content')

<table class="table table-bordered data-table" >
    <thead>
        <tr>
            <th><input type="checkbox" id="ToggleSelectAll" class="main-btn"></th>
            <th>#</th>
            <th>@lang('trans.client')</th>
            <th>@lang('trans.date')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr>
            <td>
                <input type="checkbox" class="DTcheckbox" value="{{ $Model->id }}">
            </td>
            <td>{{ $Model->id }}</td>
            <td>{{ $Model->client->name }}</td>
            <td>{{$Model->date}}</td>
            <td>
                <a href="{{ route(activeGuard().'.rides.show', $Model) }}"><i class="fa-solid fa-eye"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
