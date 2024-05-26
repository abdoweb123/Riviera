<table>
    <thead>
        <tr>
            <th></th>
            <th>@lang('trans.orderNo')</th>
            <th>@lang('trans.client')</th>
            <th>@lang('trans.phone')</th>
            <th>@lang('trans.netTotal')</th>
            <th>@lang('trans.paymentMethod')</th>
            <th>@lang('trans.time')</th>
        </tr>
    </thead>
    <tbody>
        @php($total = 0)
        @foreach ($orders[0] as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order['id'] }}</td>
                <td>
                    {{ $order->client['name'] }}
                </td>
                <td>{{ $order->client['phone'] }}</td>
                <td>{{ $order['net_total'] }} BHD</td>
                @php($total += $order['net_total'])
                <td>{{ $order->Payment['title_' . app()->getLocale()] }}</td>
                <td>{{ $order['created_at'] }}</td>
            </tr>
        @endforeach
         <tr>
            <td colspan="7" style="text-align: center!important;">
                @lang('trans.netTotal'): {{ $total }} BHD
            </td>
         </tr>
    </tbody>
</table>
