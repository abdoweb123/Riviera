<li class="nav-item @if(str_contains(Route::currentRouteName(), 'drivers')) active @endif">
    <a class="collapsed" href="#0" class="" data-bs-toggle="collapse" data-bs-target="#drivers" aria-controls="drivers" aria-expanded="true" aria-label="Toggle navigation">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-drivers-license mx-2"></i>
        </span>
        <span class="text">{{ __('trans.drivers') }}</span>
    </a>
    <ul id="drivers" class="dropdown-nav mx-4 collapse" style="">
        <li><a href="{{ route('admin.drivers.index') }}">{{ __('trans.viewAll') }}</a></li>
        <li><a href="{{ route('admin.cars-types.index') }}">{{ __('trans.cars_types') }}</a></li>
        <li><a href="{{ route('admin.cars.index') }}">{{ __('trans.cars') }}</a></li>
        <li><a href="{{ route('admin.ratings.index') }}">{{ __('trans.ratings') }}</a></li>
    </ul>
</li>