<header class="main-header">
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="{{ route('admin.home') }}" class="navbar-brand">
                    <i class="fa fa-home"></i>
                    <b class="hidden-xs">SteamWonder</b>
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    @foreach ($accessMenus as $item)
                        @if ($item->parent_id == 0 || $item->parent_id == null)
                            @php
                                $check = 0;
                                if (Request::segment(2) == $item->url_link && $item->url_link != '') {
                                    $check++;
                                }
                                foreach ($accessMenus as $sub) {
                                    if ($sub->parent_id == $item->id && Request::segment(2) == $sub->url_link && $sub->url_link != '') {
                                        $check++;
                                    }
                                }
                            @endphp
                            @if ($item->submenu > 0)
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="{{ $item->icon != '' ? $item->icon : 'fa fa-angle-right' }}"></i>
                                        {{ __($item->name) }}
                                        <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        @foreach ($accessMenus as $sub)
                                            @if ($sub->parent_id == $item->id)
                                                @if ($sub->submenu > 0)
                                                    <li class="dropdown sub {{ Request::segment(2) == $sub->url_link && $sub->url_link != '' ? 'active' : '' }}">
                                                        <a href="javascript:void(0)">
                                                            <i
                                                                class="{{ $sub->icon != '' ? $sub->icon : 'fa fa-angle-right' }}"></i>
                                                            <span>{{ __($sub->name) }}</span>
                                                            <i class="fa fa-angle-right pull-right" style="padding-top: 2px;"></i>
                                                        </a>

                                                        <ul class="dropdown-menu sub_child">
                                                            @foreach ($accessMenus as $sub_child)
                                                                @if ($sub_child->parent_id == $sub->id)
                                                                    <li
                                                                        class="{{ Request::segment(2) == $sub_child->url_link && $sub_child->url_link != '' ? 'active' : '' }}">
                                                                        <a href="/admin/{{ $sub_child->url_link }}">
                                                                            <i
                                                                                class="{{ $sub_child->icon != '' ? $sub_child->icon : 'fa fa-angle-right' }}"></i>
                                                                            <span>{{ __($sub_child->name) }}</span>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>

                                                    </li>
                                                @else
                                                    <li
                                                        class="{{ Request::segment(2) == $sub->url_link && $sub->url_link != '' ? 'active' : '' }}">
                                                        <a href="/admin/{{ $sub->url_link }}">
                                                            <i
                                                                class="{{ $sub->icon != '' ? $sub->icon : 'fa fa-angle-right' }}"></i>
                                                            <span>{{ __($sub->name) }}</span>
                                                        </a>

                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="{{ Request::segment(2) == $item->url_link ? 'active' : '' }}">
                                    <a href="/admin/{{ $item->url_link }}">
                                        <i class="{{ $item->icon != '' ? $item->icon : 'fa fa-angle-right' }}"></i>
                                        {{ __($item->name) }}
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="toggleNotify()">
                            @lang('Thông báo')
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-danger notify_read">@lang('HOT')</span>
                            {{-- <span class="notify_read">{{ $notify - count($user_notify) > 0 ? $notify - count($user_notify) : 0 }}</span> --}}
                        </a>
                        <ul class="dropdown-menu" id="toggle_notify" data-id ='1'>
                            <li>
                                <ul class="menu list_notify">
                                    {{-- @if ($notify)
                                        @foreach ($notify as $item)
                                            <li class="item_notify {{!in_array($item->id,$user_notify)?'notify':''}}" data-id="{{$item->id}}">
                                                <a href="javascript:void(0)">
                                                    <i class="fa fa-newspaper-o text-red"></i>
                                                    {{ $item->title ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif --}}
                                </ul>
                            </li>
                            <li class="footer"><a href="#" class="view_more_notify">@lang('Xem thêm')</a></li>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span>
                                {{ $admin_auth->name }}
                            </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <p>
                                    {{ $admin_auth->name }}
                                    <small>{{ $admin_auth->email }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('admin.account.change.get') }}"
                                        class="btn btn-default btn-flat">@lang('Profile')</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('admin.logout') }}"
                                        class="btn btn-default btn-flat">@lang('Logout')</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
