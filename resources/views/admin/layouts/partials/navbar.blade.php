<!--  BEGIN NAVBAR  -->
<div class="header-container container-xxl">
    <header class="header navbar navbar-expand-sm expand-header">

        <ul class="navbar-item theme-brand flex-row text-center">
            <li class="nav-item theme-logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('assets/src/assets/img/favicon-32x32.png') }}" class="navbar-logo" alt="Logo">
                </a>
            </li>
            <li class="nav-item theme-text">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"> LYRICS </a>
            </li>
        </ul>

        <ul class="navbar-item flex-row ms-lg-auto ms-0 action-area">

            {{-- Theme Toggle --}}
            <li class="nav-item theme-toggle-item">
                <a href="javascript:void(0);" class="nav-link theme-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-moon dark-mode">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-sun light-mode">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </a>
            </li>

            {{-- Notifications Dropdown --}}
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-bell">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="badge badge-success"></span>
                </a>

                <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">
                        <div class="drodpown-title notification mt-2">
                            <h6 class="d-flex justify-content-between">
                                <span class="align-self-center">Notifications</span>
                                <span class="badge badge-secondary">0 New</span>
                            </h6>
                        </div>

                        <div class="dropdown-item text-center">
                            <p class="text-muted">No new notifications</p>
                        </div>
                    </div>
                </div>
            </li>

            {{-- User Profile Dropdown --}}
            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar-container">
                        <div class="avatar avatar-sm avatar-indicators avatar-online">
                            @if(auth()->user()->profile_picture)
                                <img alt="avatar"
                                     src="{{ asset('storage/profiles/' . auth()->user()->profile_picture) }}"
                                     class="rounded-circle">
                            @else
                                <img alt="avatar"
                                     src="{{ asset('assets/src/assets/img/avatars-1.svg') }}"
                                     class="rounded-circle">
                            @endif
                        </div>
                    </div>
                </a>

                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    {{-- User Info Section --}}
                    <div class="user-profile-section">
                        <div class="media mx-auto">
                            <div class="emoji me-2">
                                &#x1F44B;
                            </div>
                            <div class="media-body">
                                <h5>{{ auth()->user()->name }}</h5>
                                <p class="text-muted small mb-0">{{ auth()->user()->role_name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Profile Link --}}
                    <div class="dropdown-item">
                        <a href="#" class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-user me-2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>My Profile</span>
                        </a>
                    </div>

                    {{-- Settings Link (Admin Only) --}}
                    @if(auth()->user()->role === 'admin')
                        <div class="dropdown-item">
                            <a href="{{ route('admin.settings.index') }}" class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-settings me-2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M12 1v6m0 6v6m6-12h-6m-6 0H1m17.5 6H18m-6 0H1m17.5 6H18m-6 0H1"></path>
                                </svg>
                                <span>Settings</span>
                            </a>
                        </div>
                    @endif

                    <div class="dropdown-divider"></div>

                    {{-- Logout --}}
                    <div class="dropdown-item">
                        <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                            @csrf
                            <a href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="d-flex align-items-center text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-log-out me-2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Log Out</span>
                            </a>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->
