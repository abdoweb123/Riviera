@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.rating'))
@section('content')


<table class="table table-bordered data-table" >
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.client_name')</th>
            <th>@lang('trans.driver_name')</th>
            <th>@lang('trans.rating')</th>
            <th>@lang('trans.comment')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $Model->client->name }}</td>
            <td>{{ $Model->driver->name }}</td>
            <td>{{ $Model->rating }}</td>
            <td>
                {{ strlen($Model->comment) > 50 ? substr($Model->comment, 0, 50) . '...' : $Model->comment }}
            </td>
            <td>
                <a href="{{ route(activeGuard().'.ratings.show',  $Model) }}"><i class="fas fa-eye"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
