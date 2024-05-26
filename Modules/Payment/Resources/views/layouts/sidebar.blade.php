<li class="nav-item @if(str_contains(Route::currentRouteName(), 'payments')) active @endif">
    <a href="{{ route(activeGuard().'.payments.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-credit-card mx-2"></i>
        </span>
        <span class="text">{{ __('trans.payments') }}</span>
    </a>
</li>