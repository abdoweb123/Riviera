<li class="nav-item @if(str_contains(Route::currentRouteName(), 'sliders')) active @endif">
    <a href="{{ route(activeGuard().'.sliders.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-regular fa-images mx-2"></i>
        </span>
        <span class="text">{{ __('trans.sliders') }}</span>
    </a>
</li>