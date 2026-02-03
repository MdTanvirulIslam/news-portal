<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{ route('admin.dashboard') }}">
                        <span style="font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            CMS
                        </span>
                    </a>
                </div>
            </div>
        </div>

        <ul class="list-unstyled menu-categories" id="accordionExample">

            <!-- Dashboard -->
            <li class="menu {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- PROFILE (Added Here) -->
            <li class="menu {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <a href="{{ route('admin.profile.edit') }}" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>My Profile</span>
                        @if(!auth()->user()->profile_completed)
                            <span class="badge badge-danger ms-2" data-bs-toggle="tooltip" title="Complete your profile">!</span>
                        @endif
                    </div>
                </a>
            </li>

            <!-- Posts -->
            <li class="menu {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <a href="#posts" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.posts.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Posts</span>
                        @if(isset($pendingPostsCount) && $pendingPostsCount > 0)
                            <span class="badge badge-warning ms-2">{{ $pendingPostsCount }}</span>
                        @endif
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.posts.*') ? 'show' : '' }}" id="posts" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.posts.index') }}">All Posts</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.posts.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.posts.create') }}">Add New</a>
                    </li>
                    @if(in_array(auth()->user()->role, ['admin', 'editor']))
                        <li>
                            <a href="{{ route('admin.posts.index') }}?status=pending">
                                Pending Approval
                                @if(isset($pendingPostsCount) && $pendingPostsCount > 0)
                                    <span class="badge badge-warning ms-2">{{ $pendingPostsCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- Categories -->
            <li class="menu {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="#categories" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Categories</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="categories" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">All Categories</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.create') }}">Add New</a>
                    </li>
                </ul>
            </li>

            <!-- Tags -->
            <li class="menu {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                <a href="#tags" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.tags.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>Tags</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.tags.*') ? 'show' : '' }}" id="tags" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.tags.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.tags.index') }}">All Tags</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.tags.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.tags.create') }}">Add New</a>
                    </li>
                </ul>
            </li>

            <!-- RSS Feed (Admin & Editor only) -->
            @if(in_array(auth()->user()->role, ['admin', 'editor']))
                <li class="menu {{ request()->routeIs('admin.rss-feeds.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.rss-feeds.index') }}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-rss">
                                <path d="M4 11a9 9 0 0 1 9 9"></path>
                                <path d="M4 4a16 16 0 0 1 16 16"></path>
                                <circle cx="5" cy="19" r="1"></circle>
                            </svg>
                            <span>RSS Feeds</span>
                        </div>
                    </a>
                </li>
            @endif

        <!-- Newsletter (Admin & Editor only) -->
            @if(in_array(auth()->user()->role, ['admin', 'editor']))
                <li class="menu {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.newsletter.subscribers.index') }}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <span>Newsletter</span>
                        </div>
                    </a>
                </li>
            @endif

        <!-- Users (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <li class="menu {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Users</span>
                        </div>
                    </a>
                </li>
            @endif

        <!-- Pages (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <li class="menu {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a href="#pages" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.pages.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <span>Pages</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.pages.*') ? 'show' : '' }}" id="pages" data-bs-parent="#accordionExample">
                        <li class="{{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.index') }}">All Pages</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.pages.create') ? 'active' : '' }}">
                            <a href="{{ route('admin.pages.create') }}">Add New</a>
                        </li>
                    </ul>
                </li>

                <!-- Sitemap -->
                <li class="menu {{ Request::is('admin/sitemap*') ? 'active' : '' }}">
                    <a href="{{ route('admin.sitemap.index') }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                            <span>Sitemap</span>
                        </div>
                    </a>
                </li>
            @endif

        <!-- Settings (Admin only) -->
            @if(auth()->user()->role === 'admin')
                <li class="menu {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m6-12h-6m6 6h-6M7 7l-5 5 5 5m10-10l5 5-5 5"></path>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <span>Settings</span>
                        </div>
                    </a>
                </li>

                <!-- Clear Cache (Admin only) -->
                <li class="menu">
                    <a href="javascript:void(0);" onclick="clearCache()" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                            <span>Clear Cache</span>
                        </div>
                    </a>
                </li>
        @endif

        <!-- Logout -->
            <li class="menu">
                <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </div>
                </a>
            </li>

        </ul>
    </nav>
</div>

<!-- Logout Form (hidden) -->
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Cache Clear Form (hidden) -->
<form id="cache-clear-form" action="{{ route('admin.cache.clear') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- SweetAlert Script for Cache Clear -->
<script>
    function clearCache() {
        Swal.fire({
            title: 'Clear Cache?',
            text: 'This will clear all application, view, and route caches.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-refresh-cw"></i> Yes, clear cache!',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    document.getElementById('cache-clear-form').submit();
                    resolve();
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }

    // Initialize tooltips
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
