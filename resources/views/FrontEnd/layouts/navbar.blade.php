<nav class="navbar navbar-expand-lg main-navbar sticky-top d-none d-lg-block">
    <ul class="navbar-nav mb-2 mb-lg-0 container">
        <!-- HOME ICON -->
        <li class="nav-item">
            <a class="nav-link {{ Request::is(currentLocale()) ? 'active' : '' }}"
               href="{{ localized_route('home.index') }}">
                <span>
                    <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                        <path d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path>
                    </svg>
                </span>
            </a>
        </li>

        <!-- DYNAMIC CATEGORIES FROM DATABASE -->
    @foreach($menuCategories as $category)
        @if($category->children->count() > 0)
            <!-- CATEGORY WITH SUBCATEGORIES (Dropdown) -->
                <li class="nav-item dropdown dropdown-new">
                    <a class="nav-link" href="{{ localized_route('category.show', ['slug' => trans_slug($category)]) }}">
                        <span>{{ trans_field($category, 'name') }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-new">
                        @foreach($category->children as $subCategory)
                            @if($subCategory->is_active && $subCategory->show_in_menu)
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ localized_route('category.show', ['slug' => trans_slug($subCategory)]) }}">
                                        {{ trans_field($subCategory, 'name') }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
        @else
            <!-- CATEGORY WITHOUT SUBCATEGORIES (Simple Link) -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ localized_route('category.show', ['slug' => trans_slug($category)]) }}">
                        <span>{{ trans_field($category, 'name') }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>

<style>
    /* Active Nav Link */
    .navbar-nav .nav-link.active {
        color: #FFD700 !important;
        font-weight: bold;
    }

    /* Dropdown Hover */
    .dropdown-new:hover .dropdown-menu-new {
        display: block;
    }

    /* Smooth Transitions */
    .nav-link {
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: #FFD700;
        transform: translateY(-2px);
    }
</style>
