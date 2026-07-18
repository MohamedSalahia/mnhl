<nav class="header-navbar navbar navbar-expand-lg align-items-center navbar-light navbar-shadow fixed-top {{ auth()->user()->dark_mode ? 'navbar-dark' : '' }}">

    <div class="navbar-container d-flex content">

        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
        </div>

        <ul class="nav navbar-nav align-items-center ml-auto">

            @php
                use App\Models\Organization;
                $selectedOrganization = session('selected_organization');
                $examinerOrganizations = Organization::query()
                    ->whereHas('branches.examiners', function ($query) {
                        $query->where('users.id', auth()->id());
                    })
                    ->get();
                $selectedBranch = session('selected_branch');
                $examinerBranches = auth()->user()->examinerBranches()
                    ->where('organization_id', $selectedOrganization['id'] ?? null)
                    ->get();

            @endphp

            {{--organization--}}
            @if($selectedOrganization)

                <li class="nav-item dropdown dropdown-organization mr-1">

                    @if($examinerOrganizations->count() > 1)

                        <a class="nav-link dropdown-toggle" id="dropdown-organization" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ficon" data-feather="briefcase"></i>
                            <span class="selected-organization">{{ $selectedOrganization->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-organization">
                            @foreach($examinerOrganizations as $organization)
                                <a class="dropdown-item {{ $organization->id == $selectedOrganization->id ? 'active' : '' }}"
                                   href="{{ route('examiner.profile.switch_organization', $organization) }}">
                                    {{ $organization->name }}
                                </a>
                            @endforeach
                        </div>

                    @else

                        <a class="nav-link" href="javascript:void(0);">
                            <i class="ficon" data-feather="briefcase"></i>
                            <span class="selected-organization">{{ $selectedOrganization->name }}</span>
                        </a>

                    @endif
                </li>

            @endif

            {{--branch--}}
            @if($selectedBranch)

                <li class="nav-item dropdown dropdown-branch mr-1">

                    @if($examinerBranches->count() > 1)

                        <a class="nav-link dropdown-toggle" id="dropdown-branch" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ficon" data-feather="git-branch"></i>
                            <span class="selected-branch">{{ $selectedBranch->name }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-branch">

                            @foreach($examinerBranches as $branch)
                                <a class="dropdown-item {{ $branch->id == $selectedBranch->id ? 'active' : '' }}"
                                   href="{{ route('examiner.profile.switch_branch', $branch) }}">
                                    {{ $branch->name }}
                                </a>
                            @endforeach
                        </div>

                    @else

                        <a class="nav-link" href="javascript:void(0);">
                            <i class="ficon" data-feather="git-branch"></i>
                            <span class="selected-branch">{{ $selectedBranch->name }}</span>
                        </a>

                    @endif
                </li>

            @endif

            {{--languages--}}
            <li class="nav-item dropdown dropdown-language">

                <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <i class="flag-icon flag-icon-{{ config('localization.supportedLocales')[app()->getLocale()]['country_flag_code'] }}"></i>
                    <span class="selected-language">@lang('languages.' . app()->getLocale())</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">

                    @foreach(config('localization.supportedLocales') as $localeCode => $properties)

                        <a class="dropdown-item" href="{{ route('examiner.profile.switch_language', ['locale' => $localeCode]) }}">

                            <i class="flag-icon flag-icon-{{ $properties['country_flag_code'] }}"></i>

                            @lang('languages.' . $localeCode)
                        </a>

                    @endforeach

                </div>
            </li>

            {{--moon--}}
            <li class="nav-item d-none d-lg-block">
                <a class="nav-link nav-link-style"
                   data-toggle-dark-model-url="{{ route('examiner.profile.toggle_dark_mode') }}"
                >
                    <i class="ficon" data-feather="{{ auth()->user()->dark_mode ? 'sun' : 'moon' }}"></i>
                </a>
            </li>

            {{--user--}}
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name font-weight-bolder">{{ auth()->user()->name }}</span>
                        <span class="user-status">@lang('users.examiner')</span>
                    </div>
                    <span class="avatar"><img class="round" src="{{ auth()->user()->image_path }}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">

                    <a class="dropdown-item" href="{{ route('examiner.profile.edit') }}" wire:navigate>
                        <i class="mr-50" data-feather="user"></i>
                        @lang('users.profile')
                    </a>

                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                        <i class="mr-50" data-feather="power"></i>
                        @lang('site.logout')
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                </div>
            </li>

        </ul>
    </div>
</nav>
<!-- END: Header-->
