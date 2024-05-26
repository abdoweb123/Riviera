<li class="nav-item @if(str_contains(Route::currentRouteName(), 'rides')) active @endif">
    <a href="{{ route(activeGuard().'.rides.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-route mx-2"></i>
        </span>
        <span class="text">{{ __('trans.rides') }}</span>
    </a>
</li>