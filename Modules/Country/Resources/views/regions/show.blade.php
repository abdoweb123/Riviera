@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', $Country->title() . ' >> ' . $Region->title())
@section('content')

<div class="row">
    <div class="my-2 col-12 col-md-6">
        <a href="{{ route(activeGuard().'.country.region.cities.create',['country'=>$Country,'region'=>$Region]) }}" class="main-btn btn-hover text-center mx-auto px-5">@lang('trans.add_new') <i class="fa-solid fa-plus mx-1"></i></a>
    </div>
</div>
<h1>@lang('trans.cities')</h1>
<table class="table dataTable  data-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('trans.title_ar')</th>
            <th>@lang('trans.title_en')</th>
            <th>@lang('trans.visibility')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Region->Cities as $City)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $City->title_ar }}</td>
            <td>{{ $City->title_en }}</td>
            <td>
                <input class="toggle" type="checkbox" onclick="toggleswitch({{ $City->id }},'countries')" @if ($City->status) checked @endif>
            </td>
            <td>
                <a href="{{ route(activeGuard().'.country.region.cities.edit',['country'=>$Country,'region'=>$Region,'city'=>$City]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                <form class="formDelete" method="POST" action="{{ route(activeGuard().'.country.region.cities.destroy',['country'=>$Country,'region'=>$Region,'city'=>$City]) }}">
                    @csrf
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
