@isset($arMenu)
    <div
        id="m_ver_menu"
        class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
        m-menu-vertical="1"
        m-menu-scrollable="0" m-menu-dropdown-timeout="500"
    >
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            @foreach($arMenu as $arFirstLevel)
                <li class="m-menu__item  m-menu__item--submenu {{isset($arFirstLevel['current']) && $arFirstLevel['current'] == 'Y'?'m-menu__item--open':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                    <a href="javascript:void (0);" class="m-menu__link m-menu__toggle">
                        @isset($arFirstLevel['icon'])
                            <i class="m-menu__link-icon {{$arFirstLevel['icon']}}"></i>
                        @endisset
                        <span class="m-menu__link-text">
										{{$arFirstLevel['name']}}
									</span>
                        <i class="m-menu__ver-arrow la la-angle-right"></i>
                    </a>
                    @isset($arFirstLevel['items'])
                        <div class="m-menu__submenu ">
                            <span class="m-menu__arrow"></span>
                            <ul class="m-menu__subnav">
                                @foreach($arFirstLevel['items'] as $arItem)
                                    <li class="m-menu__item {{isset($arItem['current']) && $arItem['current'] == 'Y'?'m-menu__item--active':''}}" aria-haspopup="true">
                                        <a href="{{$arItem['url']}}" class="m-menu__link ">
                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                <span></span>
                                            </i>
                                            <span class="m-menu__link-text">
													{{$arItem['name']}}
												</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endisset
                </li>
            @endforeach
                <li class="m-menu__item  m-menu__item--submenu">
                    <a href="{{route('logout')}}" class="m-menu__link m-menu__toggle">
                            <i class="m-menu__link-icon flaticon-logout"></i>
                        <span class="m-menu__link-text">
										Выход
									</span>

                    </a>

                </li>
        </ul>
    </div>
@endisset
