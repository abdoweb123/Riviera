<li class="nav-item @if(str_contains(Route::currentRouteName(), 'complaint')) active @endif">
    <a href="{{ route(activeGuard().'.complaint.index') }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-person-circle-exclamation mx-2"></i>
        </span>
        <span class="text">{{ __('trans.complaints') }}</span>
    </a>
</li>