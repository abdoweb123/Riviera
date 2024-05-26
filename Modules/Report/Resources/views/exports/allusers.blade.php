<table>
    <thead>
        <tr>
            <th>@lang('trans.name')</th>
            <th>@lang('trans.phone')</th>
            <th>@lang('trans.email')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as  $client)
            <tr>
                <td>{{ $client->name }}</td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
