@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.cars_types'))
@section('content')

<table class="table">
    <tr>
        <td class="text-center">
            {{ $Model['title_en'] }}
        </td>
        <td class="text-center">
            {{ $Model['title_ar'] }}
        </td>
    </tr>
    <tr>
        <td class="text-center">
            @if ($Model->status) @lang('trans.visible') @else @lang('trans.hidden') @endif
        </td>
        <td class="text-center">
            <img src="{{asset($Model->image)}}" alt="" style="width: 100px; height:100px;">
        </td>
    </tr>
</table>

@endsection
