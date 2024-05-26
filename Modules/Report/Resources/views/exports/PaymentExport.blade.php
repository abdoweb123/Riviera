<table>
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.type')</th>
            <th>@lang('trans.orderNo')</th>
            <th>@lang('trans.client')</th>
            <th>@lang('trans.value')</th>
            <th>Result</th>
            <th>Transaction Number</th>
            <th>@lang('trans.time')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders[0] as $report)
            <tr class="gradeX {{ $report['id'] }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $report['type'] }}</td>
                <td>{{ $report['order_id'] }}</td>
                <td>{{ $report['client']['name'] }}</td>
                <td>{{ $report['value'] }}</td>
                <td>{{ $report['result'] }}</td>
                <td>{{ $report['transaction_number'] }}</td>
                <td>{{ $report['created_at'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
