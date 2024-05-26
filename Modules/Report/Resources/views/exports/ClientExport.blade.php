<table>
    <thead>
        <tr>
            <th>@lang('trans.orderNo')</th>
            <th style="text-align:center;">@lang('trans.phone')</th>
            <th style="text-align:center;">@lang('trans.netTotal')</th>
            <th style="text-align:center;">@lang('trans.paymentMethod')</th>
            <th style="text-align:center;">@lang('trans.time')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders[0] as  $order)
            <tr>
                <td>{{ $order['id'] }}</td>
                <td>{{ $order->client['phone'] }}</td>
                <td>{{ $order['net_total'] }} BHD</td>
                <td>{{ $order->Payment['title_' . app()->getLocale()] }}</td>
                <td>{{ $order['created_at'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
