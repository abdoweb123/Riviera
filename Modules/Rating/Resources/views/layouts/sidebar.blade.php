<li class="nav-item @if(str_contains(Route::currentRouteName(), 'contacts')) active @endif">
    <a href="{{ route(activeGuard().'.contacts.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-earth-asia mx-2"></i>
        </span>
        <span class="text">{{ __('trans.Contact Us') }}</span>
    </a>
</li>
