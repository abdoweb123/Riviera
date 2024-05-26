@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.countries'))
@section('content')

<table class="table dataTable  data-table" >
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.title_ar')</th>
            <th>@lang('trans.title_en')</th>
            <th>@lang('trans.image')</th>
            <th>@lang('trans.status')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Countries as $Country)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><a  href="{{ route(activeGuard().'.countries.show', $Country) }}">{{ $Country->title_ar }}</a></td>
            <td><a  href="{{ route(activeGuard().'.countries.show', $Country) }}">{{ $Country->title_en }}</a></td>
            <td><img src="{{ asset($Country->image) }}" style="max-width: 80px"></td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $Country->id }},'countries')" @if ($Country->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.countries.edit', $Country) }}"><i class="fa-solid fa-pen-to-square"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
