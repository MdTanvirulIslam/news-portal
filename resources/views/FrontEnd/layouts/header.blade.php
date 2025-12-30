<header class="logo-section">
    <div class="container d-flex flex-column flex-lg-row justify-content-between">
        <!-- LEFT SECTION: Hamburger, Search, Date -->
        <div class="left order-1 order-lg-0 sticky-top">
            <div class="d-flex align-items-center justify-content-between justify-content-lg-start gap-2">
                <span class="more_main_menu" role="button" tabindex="0" aria-label="Toggle navigation menu">
                    <i class="fa fa-bars"></i>
                    <i class="fa fa-times"></i>
                </span>
                <span class="search_icon"><i class="fa fa-search"></i></span>
            </div>

            <!-- DYNAMIC LOCALIZED DATE -->
            <div class="todays_time d-none d-lg-block">
                <i class="fa fa-calendar-days"></i>
                @php
                    $locale = currentLocale();
                    $now = \Carbon\Carbon::now();

                    if ($locale == 'bn') {
                        // Bangla date with Bangla calendar
                        echo $now->locale('bn')->translatedFormat('l, d F Y');
                    } else {
                        // English date
                        echo $now->format('l, d F Y');
                    }
                @endphp
            </div>
        </div>

        <!-- MIDDLE SECTION: Logo -->
        <div class="middle order-2 order-lg-1">
            <a href="{{ localized_route('home.index') }}">
                @if(!empty($logoSettings->main_logo))
                    <img height="60"
                         src="{{ asset('storage/' . $logoSettings->main_logo) }}"
                         alt="{{ $logoSettings->main_logo_alt ?? 'Logo' }}" />
                @else
                <!-- Fallback logo if no logo uploaded -->
                    <img height="60"
                         src="{{ asset('FrontEnd/images/logo.svg') }}"
                         alt="{{ $websiteSettings->website_title ?? 'News Portal' }}" />
                @endif
            </a>
        </div>

        <!-- RIGHT SECTION: Social Media & Language Switcher -->
        <div class="right order-0 order-lg-2">
            <div class="header_social_wrap">
                <!-- DYNAMIC SOCIAL MEDIA LINKS -->
                <ul class="d-flex align-items-center justify-content-lg-end gap-2 gap-sm-3 list-unstyled">
                    @if(!empty($websiteSettings->facebook_url))
                        <li>
                            <a href="{{ $websiteSettings->facebook_url }}" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->twitter_url))
                        <li>
                            <a href="{{ $websiteSettings->twitter_url }}" target="_blank" rel="noopener noreferrer">
                                <span><i class="fab fa-twitter"></i></span>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->linkedin_url))
                        <li>
                            <a href="{{ $websiteSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer">
                                <span><i class="fab fa-linkedin-in"></i></span>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->rss_url))
                        <li>
                            <a href="{{ $websiteSettings->rss_url }}" target="_blank" rel="noopener noreferrer">
                                <span><i class="fas fa-rss"></i></span>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->whatsapp_url))
                        <li>
                            <a href="{{ $websiteSettings->whatsapp_url }}" target="_blank" rel="noopener noreferrer">
                                <span><i class="fab fa-whatsapp"></i></span>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->youtube_url))
                        <li>
                            <a href="{{ $websiteSettings->youtube_url }}" target="_blank" rel="noopener noreferrer">
                                <span><i class="fab fa-youtube"></i></span>
                            </a>
                        </li>
                @endif

                <!-- MOBILE LANGUAGE SWITCHER -->
                    <li class="d-lg-none ms-auto">
                        <a href="{{ switchLocaleRoute('bn') }}"
                           class="text-white {{ currentLocale() == 'bn' ? 'active-lang' : '' }}">
                            BN
                        </a>
                        |
                        <a href="{{ switchLocaleRoute('en') }}"
                           class="text-white {{ currentLocale() == 'en' ? 'active-lang' : '' }}">
                            EN
                        </a>
                    </li>
                </ul>

                <!-- DESKTOP LANGUAGE SWITCHER -->
                @php
                    // Auto-detect if we're on a post, category, or tag page
                    $model = $post ?? $category ?? $tag ?? null;
                @endphp

                <ul class="d-none d-lg-flex align-items-center list-unstyled header_top_menu">
                    <li>
                        <a href="{{ switchLocaleRoute('bn', $model) }}"
                           class="{{ currentLocale() == 'bn' ? 'active' : '' }}">
                            বাংলা
                        </a>
                    </li>
                    <li>
                        <a href="{{ switchLocaleRoute('en', $model) }}"
                           class="{{ currentLocale() == 'en' ? 'active' : '' }}">
                            ENGLISH
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Active Language Styling -->
<style>
    /* Desktop Active Language */
    .header_top_menu li a.active {
        color: #FFD700 !important;
        font-weight: bold;
        border-bottom: 2px solid #FFD700;
    }

    /* Mobile Active Language */
    .text-white.active-lang {
        font-weight: bold !important;
        text-decoration: underline;
        color: #FFD700 !important;
    }

    /* Social Media Hover Effect */
    .header_social_wrap ul li a {
        transition: all 0.3s ease;
    }

    .header_social_wrap ul li a:hover {
        color: #FFD700;
        transform: scale(1.1);
    }
</style>
