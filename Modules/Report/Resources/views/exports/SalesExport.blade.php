<table  style="text-align:center;">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.orderNo')</th>
            <th style="text-align:center;">@lang('trans.paymentMethod')</th>
            <th style="text-align:center;">@lang('trans.subTotal')</th>
            <th style="text-align:center;">@lang('trans.netTotal')</th>
        </tr>
    </thead>
    <tbody>
        @php($net_total = 0)
        @php($sub_total = 0)
        @foreach ($orders[0] as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order['id'] }}</td>
                <td>{{ $order->Payment['title_' . app()->getLocale()] }}</td>
                <td>{{ $order->sub_total }} {{  Country()->currancy_code }}</td>
                @php($sub_total += $order['sub_total'])
                <td>{{ $order['net_total'] }} {{  Country()->currancy_code }}</td>
                @php($net_total += $order['net_total'])
            </tr>
        @endforeach
         <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                @lang('trans.subTotal'): {{ $sub_total }} {{  Country()->currancy_code }}
            </td>
            <td>
                @lang('trans.netTotal'): {{ $net_total }} {{  Country()->currancy_code }}
            </td>
        </tr>
    </tbody>
</table>
