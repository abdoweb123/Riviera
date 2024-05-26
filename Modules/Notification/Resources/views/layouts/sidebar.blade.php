<li class="nav-item @if(str_contains(Route::currentRouteName(), 'notifications')) active @endif">
    <a href="{{ route(activeGuard().'.notifications.create') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-bell mx-2"></i>
        </span>
        <span class="text">{{ __('trans.notifications') }}</span>
    </a>
</li>