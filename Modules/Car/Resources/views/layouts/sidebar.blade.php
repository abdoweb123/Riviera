<li class="nav-item @if(str_contains(Route::currentRouteName(), 'car')) active @endif">
    <a href="{{ route(activeGuard().'.cars.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-car mx-2"></i>
        </span>
        <span class="text">{{ __('trans.cars') }}</span>
    </a>
</li>