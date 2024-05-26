<li class="nav-item @if(str_contains(Route::currentRouteName(), 'clients')) active @endif">
    <a href="{{ route(activeGuard().'.clients.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-user-tag mx-2"></i>
        </span>
        <span class="text">{{ __('trans.clients') }}</span>
    </a>
</li>