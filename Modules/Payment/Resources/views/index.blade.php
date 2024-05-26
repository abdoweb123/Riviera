@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.paymentMethods'))
@section('content')

<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.title')</th>
            <th>@lang('trans.image')</th>
            <th>@lang('trans.status')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $Model->title() }}</td>
            <td><img src="{{ asset($Model->image  ?? setting('logo')) }}" style="max-width: 200px"></td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $Model->id }},'payments')" @if ($Model->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.payments.edit', $Model) }}"><i class="fa-solid fa-pen-to-square"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
