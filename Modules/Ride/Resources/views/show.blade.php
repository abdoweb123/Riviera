@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.rides'))
@section('content')

<table class="table">
    <tr>
        <td class="text-center">
            <span class="text-bold">@lang('trans.ride_number'):</span>  #{{ $ride['id'] }}
        </td>
        <td class="text-center">
           <span class="text-bold"> @lang('trans.date'):</span> {{$ride->date}}
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.start_time'):</span>    {{ Carbon\Carbon::parse($ride->start_time)->format('h:i A') }}
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.end_time'):</span>    {{ Carbon\Carbon::parse($ride->start_time)->format('h:i A') }}
        </td>
        <td>
            <span class="text-bold">@lang('trans.client_name'):</span>
            <a href="{{ route(activeGuard().'.clients.show', ['client'=>$ride->client_id]) }}"> {{ $ride->client->name ?? ''}} </a>
        </td>
    </tr>

    <tr>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.driver_name'):</span>
            <a href="{{ route(activeGuard().'.drivers.show', ['driver'=>$ride->driver_id]) }}"> {{ $ride->driver->name ?? ''}} </a>
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.car_type'):</span> {{number_format($ride->distance)}}
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.rating'):</span> {{$ride->rating}}
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.paymentMethod'):</span>  {{ $ride->payment['title_'.lang()] }}
        </td>
        <td class="text-center">
            <span class="text-bold"> @lang('trans.cost'):</span> {{ format_number($ride->cost) }}
        </td>
    </tr>

    <tr>

        @if($ride->canceled == 1)
            <td class="text-center">
                <span class="text-bold"> @lang('trans.ride_is_canceled')</span>
            </td>
            <td class="text-center">
                <span class="text-bold"> @lang('trans.reason'):</span>
                {{$ride->rideCancelations->cancellationReason['reason_'.lang()]}}
            </td>
        @else

        @endif

{{--        @if(Carbon\Carbon::now()->toDateString() < $ride->date)--}}
{{--            <td class="text-center"> future </td>--}}
{{--        @else--}}

{{--        @endif--}}
    </tr>
</table>

@endsection
