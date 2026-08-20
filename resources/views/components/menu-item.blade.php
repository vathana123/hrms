@switch($menu->type)
    @case(\App\Enums\MenuType::GROUP_MENU)
        <a href="{{ route($menu->route_url . '.index') }}"
            class="qmenu-link {{ request()->is($menu->route_url . '*') ? 'active' : '' }}">
            {!! $menu->icon !!}
            {{ $menu->name }}
        </a>
        @break

    @case(\App\Enums\MenuType::SINGLE_MENU)
        <a href="{{ route($menu->route_url) }}"
            class="qmenu-link {{ request()->routeIs($menu->route_url) ? 'active' : '' }}"
            @if($menu->open_in_new_tab) target="_blank" @endif>
            {!! $menu->icon !!}
            {{ $menu->name }}
        </a>
        @break

    @default
        <li class="qmenu-item mt-3">
            <div class="qmenu-text">{{ $menu->name }}</div>
        </li>

@endswitch