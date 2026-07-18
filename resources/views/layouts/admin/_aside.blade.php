<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('admin.home') }}">
                    <span class="brand-logo">
                        <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" height="24">
                                <defs>
                                    <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                                        <stop stop-color="#000000" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                    <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                                        <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                        <stop stop-color="#FFFFFF" offset="100%"></stop>
                                    </lineargradient>
                                </defs>
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                        <g id="Group" transform="translate(400.000000, 178.000000)">
                                            <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                                            <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                                            <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                                            <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                                            <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                    </span>
                    <h2 class="brand-text">منهل</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="{{ request()->is('*home*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('admin.home') }}" wire:navigate>
                    <i data-feather="mail"></i><span class="menu-title text-truncate">@lang('site.home')</span>
                </a>
            </li>

            {{--roles--}}
            @if (auth()->user()->hasPermission('read_roles'))
                <li class="{{ request()->is('*roles*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.roles.index') }}" wire:navigate>
                        <i data-feather="lock"></i><span class="menu-title text-truncate">@lang('roles.roles')</span>
                    </a>
                </li>
            @endif

            {{--admins--}}
            @if (auth()->user()->hasPermission('read_admins'))
                <li class="{{ request()->is('*admins*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.admins.index') }}" wire:navigate>
                        <i data-feather="users"></i><span class="menu-title text-truncate">@lang('admins.admins')</span>
                    </a>
                </li>
            @endif

            {{--organizations--}}
            @if (auth()->user()->hasPermission('read_organizations'))
                <li class="{{ request()->is('*organizations*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.organizations.index') }}" wire:navigate>
                        <i data-feather="briefcase"></i><span class="menu-title text-truncate">@lang('organizations.organizations')</span>
                    </a>
                </li>
            @endif

            {{--branches--}}
            @if (auth()->user()->hasPermission('read_branches'))
                <li class="{{ request()->is('*branches*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.branches.index') }}" wire:navigate>
                        <i data-feather="git-branch"></i><span class="menu-title text-truncate">@lang('branches.branches')</span>
                    </a>
                </li>
            @endif

            {{--profile--}}
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="user"></i><span class="menu-title text-truncate">@lang('users.profile')</span></a>
                <ul class="menu-content">
                    <li class="{{ request()->is('*profile*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.profile.edit') }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('users.profile')</span>
                        </a>
                    </li>

                    <li class="{{ request()->is('*password*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.profile.password.edit') }}" wire:navigate>
                            <i data-feather="circle"></i><span class="menu-item text-truncate">@lang('users.change_password')</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{--settings--}}
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Dashboards">@lang('settings.settings')</span></a>
                <ul class="menu-content">

                    @if (auth()->user()->hasPermission('read_settings'))
                        <li class="{{ request()->is('*settings/general_data*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.settings.general_data') }}">
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('settings.general')</span>
                            </a>
                        </li>
                    @endif

                    {{--currencies--}}
                    @if (auth()->user()->hasPermission('read_currencies'))
                        <li class="{{ request()->is('*currencies*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.currencies.index') }}" wire:navigate>
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('currencies.currencies')</span>
                            </a>
                        </li>
                    @endif

                    {{--countries--}}
                    @if (auth()->user()->hasPermission('read_countries'))
                        <li class="{{ request()->is('*countries*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.countries.index') }}" wire:navigate>
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('countries.countries')</span>
                            </a>
                        </li>
                    @endif

                    {{--governorates--}}
                    @if (auth()->user()->hasPermission('read_governorates'))
                        <li class="{{ request()->is('*governorates*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.governorates.index') }}" wire:navigate>
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('governorates.governorates')</span>
                            </a>
                        </li>
                    @endif

                    {{--areas--}}
                    @if (auth()->user()->hasPermission('read_areas'))
                        <li class="{{ request()->is('*areas*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.areas.index') }}" wire:navigate>
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('areas.areas')</span>
                            </a>
                        </li>
                    @endif

                    {{--nationalities--}}
                    @if (auth()->user()->hasPermission('read_nationalities'))
                        <li class="{{ request()->is('*nationalities*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.nationalities.index') }}" wire:navigate>
                                <i data-feather="circle"></i>
                                <span class="menu-item text-truncate">@lang('nationalities.nationalities')</span>
                            </a>
                        </li>
                    @endif

                    {{--whatsapp templates--}}
                    @if (auth()->user()->hasPermission('read_whatsapp_templates'))
                        <li class="{{ request()->is('*whatsapp-templates*') ? 'active' : '' }}">
                            <a class="d-flex align-items-center" href="{{ route('admin.whatsapp_templates.index') }}" wire:navigate>
                                <i data-feather="message-circle"></i>
                                <span class="menu-item text-truncate">@lang('whatsapp_templates.whatsapp_templates')</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </li>

        </ul>
    </div>
</div>
<!-- END: Main Menu-->
