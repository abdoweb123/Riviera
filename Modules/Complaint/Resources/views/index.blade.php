@extends(ucfirst(activeGuard()).'.Layouts.layout')

@section('pagetitle', __('trans.complaints'))
@section('content')

<div class="row">
    <div class="my-2 col-6 text-sm-end">
        <button type="button" id="DeleteSelected" onclick="DeleteSelected('complaints')" class="btn btn-danger" disabled>@lang('trans.Delete_Selected')</button>
    </div>
</div>
<table class="table table-bordered data-table" >
    <thead>
        <tr>
            <th><input type="checkbox" id="ToggleSelectAll" class="main-btn"></th>
            <th>#</th>
            <th>@lang('trans.title')</th>
            <th>@lang('trans.full_name')</th>
            <th>@lang('trans.email')</th>
            <th>@lang('trans.complaint')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Models as $Model)
        <tr>
            <td>
                <input type="checkbox" class="DTcheckbox" value="{{ $Model->id }}">
            </td>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $Model->title }}</td>
            <td>{{ $Model->full_name }}</td>
            <td>{{ $Model->email }}</td>
            <td>
                {{ strlen($Model->complaint) > 50 ? substr($Model->complaint, 0, 50) . '...' : $Model->complaint }}
            </td>
            <td>
                <a href="{{ route(activeGuard().'.complaints.show', $Model) }}"><i class="fa-solid fa-eye"></i></a>
                <form class="formDelete" method="POST" action="{{ route(activeGuard().'.complaints.destroy', $Model) }}">
                    @csrf
                    @method('DELETE')
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="button" class="btn btn-flat show_confirm" data-toggle="tooltip" title="Delete">
                        <i class="fa-solid fa-eraser"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
