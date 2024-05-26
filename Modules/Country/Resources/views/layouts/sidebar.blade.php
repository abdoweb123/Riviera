<li class="nav-item @if(str_contains(Route::currentRouteName(), 'countries')) active @endif">
    <a class="collapsed" href="{{ route(activeGuard().'.countries.index') }}" class="" >
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-earth-asia mx-2"></i>
        </span>
        <span class="text">{{ __('trans.countries') }}</span>
    </a>
</li>