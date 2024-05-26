@extends(ucfirst(activeGuard()).'.Layouts.layout')
@section('pagetitle', __('trans.notifications'))
@section('content')
<form method="POST" action="{{ route(activeGuard().'.notifications.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <label for="title_ar">@lang('trans.title_ar')</label>
            <input type="text" name="title_ar" id="title_ar" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="title_en">@lang('trans.title_en')</label>
            <input type="text" name="title_en" id="title_en" class="form-control" required>
        </div>
        
        <div class="col-md-6">
            <label class="d-flex justify-content-between">
                <span>@lang('trans.clients')</span>
                <span class="point text-danger" id="select_all_clients">@lang('trans.select_all')</span>
            </label>
            <select class="form-control Multi-Select" name="clients[]" id="clients" multiple>
                @foreach($Clients as $Client)
                    <option value="{{ $Client->id }}">{{ $Client->name }} {{ $Client->phone ?? $Client->email  }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="d-flex justify-content-between">
                <span>@lang('trans.drivers')</span>
                <span class="point text-danger" id="select_all_drivers">@lang('trans.select_all')</span>
            </label>
            <select class="form-control Multi-Select" name="drivers[]" id="drivers" multiple>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->name }} {{ $driver->phone ?? $driver->email  }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 my-4">
            <div class="button-group my-4 text-center">
                <button type="submit" class="main-btn btn-hover w-100 text-center">
                    {{ __('trans.Submit') }}
                </button>
            </div>
        </div>
    </div>
</form>

@include('MultiSelect')
@push('js')
<script>
    $(document).on('change', '#type', function() {
        $("#branches option:selected").prop("selected", false);
        $('.Multi-Select').trigger('change.select2');
        if($( "#type option:selected" ).val() == 'branch'){
            $( ".branches" ).removeClass('d-none');   
        }else{
            $( ".branches" ).addClass('d-none');
        }
    });
    $(document).on('click', '#select_all_branches', function() {
        $("#branches option").prop("selected", true);
        $('.Multi-Select').trigger('change.select2');
    });
    $(document).on('click', '#select_all_clients', function() {
        $("#clients option").prop("selected", true);
        $('.Multi-Select').trigger('change.select2');
    });
    $(document).on('click', '#select_all_admins', function() {
        $("#admins option").prop("selected", true);
        $('.Multi-Select').trigger('change.select2');
    });
    $(document).on('click', '#select_all_employers', function() {
        $("#employers option").prop("selected", true);
        $('.Multi-Select').trigger('change.select2');
    });
</script>
@endpush
@endsection
