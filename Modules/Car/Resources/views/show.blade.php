@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.cars'))
@section('content')

<table class="table">
    <tr>
        <td class="text-center">
            {{ $Model['title_en'] }}
        </td>
        <td class="text-center">
            {{ $Model['title_ar'] }}
        </td>
        <td>
            {{ $Model->driver->name ?? ''}}
        </td>
        <td>
            {{ $Model->carType['title_'.lang()] ?? ''}}
        </td>
        <td class="text-center">
            @if ($Model->status) @lang('trans.visible') @else @lang('trans.hidden') @endif
        </td>
    </tr>
</table>

@endsection
