<li class="nav-item @if(str_contains(Route::currentRouteName(), 'setting')) active @endif">
    <a href="{{ route(activeGuard().'.settings.index',['type'=>"publicSettings"]) }}">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-gears mx-2"></i>
        </span>
        <span class="text">{{ __('trans.settings') }}</span>
    </a>
</li>
@if(Settings()->whereNotIn('type',['publicSettings','payments_integration'])->unique('type')->count())
<li class="nav-item @if(str_contains(Route::currentRouteName(), 'contact_setting')) active @endif">
    <a class="collapsed" href="#0" class="" data-bs-toggle="collapse" data-bs-target="#contact_settings" aria-controls="contact_settings" aria-expanded="true" aria-label="Toggle navigation">
        <span class="icon text-center">
            <i style="width: 20px;" class="fa-solid fa-circle-nodes mx-2"></i>
        </span>
        <span class="text">{{ __('trans.side_pages') }}</span>
    </a>
    <ul id="contact_settings" class="dropdown-nav mx-4 collapse" style="">
        @foreach (Settings()->whereNotIn('type',['publicSettings','payments_integration'])->unique('type') as $item)
        <li><a href="{{ route(activeGuard().'.settings.index',['type'=>"$item->type"]) }}">{{ __('trans.'.$item->type.'') }}</a></li>
        @endforeach
    </ul>
</li>
@endif