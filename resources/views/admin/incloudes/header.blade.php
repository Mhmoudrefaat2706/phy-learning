@php
    $logoWidth = "auto";
    $logoHeight = "100%";
@endphp

<header id="page-topbar" style="height: 60px;">
    <div class="navbar-header d-flex align-items-center" style="height: 100%;">
        <div class="d-flex align-items-center" style="height: 100%;">

            @if (isset($setting) && $setting->logo)
                <div class="p-1" style="height: 100%;">
                    <a href="{{ url('/') }}" class="logo"
                       style="height: 100%; display: flex; align-items: center;">
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo"
                             style="height: 100%; width: auto; object-fit: contain;">
                    </a>
                </div>
            @endif

            <button type="button" class="btn btn-sm font-size-24 header-item waves-effect px-3" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
        </div>

        <div class="d-flex">

            <!-- Search (mobile) -->
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                     aria-labelledby="page-header-search-dropdown">
                    <form class="p-3">
                        <div class="m-0 mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{{ __('Search ...') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fullscreen -->
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    @if ($unreadCount > 0)
                        <span class="noti-dot"></span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-center p-0"
                     aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0">{{ __('Notifications') }}</h6>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('admin.messages.index') }}" class="small">{{ __('View All') }}</a>
                            </div>
                        </div>
                    </div>

                    <div data-simplebar style="max-height: 230px;">
                        @forelse($unreadMessages as $msg)
                            <a href="{{ route('admin.messages.index') }}#messageRow{{ $msg->id }}"
                               class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar-xs me-3">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">{{ $msg->name }}</h6>
                                        <div class="font-size-12 text-muted">
                                            <p class="mb-0">
                                                {{ \Illuminate\Support\Str::limit($msg->message, 50) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-2 text-center">
                                <span class="text-muted">{{ __('No new messages') }}</span>
                            </div>
                        @endforelse
                    </div>

                    <div class="border-top p-2">
                        <div class="d-grid">
                            <a class="btn btn-sm btn-link font-size-14 text-center"
                               href="{{ route('admin.messages.index') }}">
                                <i class="mdi mdi-arrow-right-circle me-1"></i> {{ __('All Messages') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect d-flex align-items-center"
                        id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('admin')->user()->name) }}&background=0D8ABC&color=fff&size=64"
                         alt="{{ Auth::guard('admin')->user()->name }}" width="32" height="32">

                    <span class="d-none d-xl-inline-block ms-2">{{ Auth::guard('admin')->user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block ms-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-center">
                    {{-- <a class="dropdown-item" href="#">
                        <i class="ri-user-line me-1 align-middle"></i> {{ __('Profile') }}
                    </a> --}}
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="ri-settings-2-line me-1 align-middle"></i> {{ __('Settings') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="ri-shut-down-line text-danger me-1 align-middle"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
