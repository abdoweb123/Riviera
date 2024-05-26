@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.complaint'))
@section('content')

<table class="table">
    <tr>
        <td class="text-center">
            <div class="row justify-content-between">
                <div class="col text-center"> {{ $Model['title'] }}</div>
                <div class="col text-center"> {{ $Model['full_name'] }}</div>
                <div class="col text-center"> {{ $Model['email'] }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            {{ $Model['complaint'] }}
        </td>
    </tr>


</table>

@endsection

