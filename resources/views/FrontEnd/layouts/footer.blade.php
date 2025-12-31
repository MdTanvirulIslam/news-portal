<footer class="footer">
    <div class="container custom-container">
        {{-- FOOTER NAVIGATION (Pages) --}}
        <div class="row custom-row">
            <div class="col-sm-12 footer__nav text-center">
                <ul class="list-unstyled">
                    @forelse($footerPages as $page)
                        <li>
                            <a href="{{ route('page.show', ['locale' => currentLocale(), 'slug' => trans_slug($page)]) }}">
                                {{ trans_field($page, 'title') }}
                            </a>
                        </li>
                    @empty
                        {{-- Default static links if no pages in database --}}
                        <li><a href="#">
                                @if(currentLocale() == 'bn')
                                    আমাদের সম্পর্কে
                                @else
                                    About Us
                                @endif
                            </a></li>
                        <li><a href="#">
                                @if(currentLocale() == 'bn')
                                    ব্যবহার বিধি
                                @else
                                    Terms of Use
                                @endif
                            </a></li>
                        <li><a href="#">
                                @if(currentLocale() == 'bn')
                                    গোপনীয়তা নীতি
                                @else
                                    Privacy Policy
                                @endif
                            </a></li>
                        <li><a href="#">
                                @if(currentLocale() == 'bn')
                                    যোগাযোগ
                                @else
                                    Contact
                                @endif
                            </a></li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- FOOTER CONTENT (Logo, Contact Info, Phone Numbers) --}}
        <div class="footer__content">
            <div class="row custom-row">
                {{-- LOGO SECTION --}}
                <div class="col-md-4 custom-padding border border-left-0 d-flex align-items-center">
                    <div class="footer__brand">
                        @if(!empty($logoSettings->main_logo))
                            <img class="img-fluid"
                                 src="{{ asset('storage/' . $logoSettings->main_logo) }}"
                                 alt="{{ $websiteSettings->website_title_bn ?? 'Logo' }}" />
                        @else
                            <img class="img-fluid"
                                 src="{{ asset('images/logo.png') }}"
                                 alt="{{ $websiteSettings->website_title_bn ?? 'Logo' }}" />
                        @endif
                    </div>
                </div>

                {{-- CONTACT INFO SECTION --}}
                <div class="col-md-4 custom-padding border border-left-0 d-flex align-items-center"
                     style="padding-left: 5px; padding-right: 5px">
                    <div class="footer__info">
                        @if(currentLocale() == 'bn')
                            <p style="font-size: 20px">সম্পাদক ও প্রকাশক: হাশেম রেজা</p>
                            <p>বার্তা ও বাণিজ্যিক কার্যালয়: ১২৮, মতিঝিল, বা/এ, ঢাকা-১০০০।</p>
                            <p>ফোন: পিএবিএক্স- ০২-২২৩৩৫৯৩২৫, ০২-২২৩৩৫৯৩২৬<br /></p>
                            <p>ইমেইল: takwasoft@gmail.com, takwasoftonline@gmail.com</p>
                        @else
                            <p style="font-size: 20px">Editor & Publisher: Hashem Reza</p>
                            <p>News & Commercial Office: 128, Motijheel, C/A, Dhaka-1000.</p>
                            <p>Phone: PABX- 02-22335932৫, 02-22335932৬<br /></p>
                            <p>Email: takwasoft@gmail.com, takwasoftonline@gmail.com</p>
                        @endif
                    </div>
                </div>

                {{-- PHONE NUMBERS SECTION --}}
                <div class="col-md-4 custom-padding border border-left-0 border-right-0">
                    <div class="footer__info">
                        @if(currentLocale() == 'bn')
                            <p>
                                নিউজ (প্রিন্ট) : ০১৯০৪-১০০৭১৯<br />
                                নিউজ (অনলাইন) : ০১৯০৪-১০০৭৩৬<br />
                                সার্কুলেশন : ০১৯০৪-১০০৭৪৪<br />
                                বিজ্ঞাপন : ০১৯১১-১২৫৭১২<br />
                                অফিস : ০১৯০৪-১০০৭০৩
                            </p>
                        @else
                            <p>
                                News (Print) : 01904-100719<br />
                                News (Online) : 01904-100736<br />
                                Circulation : 01904-100744<br />
                                Advertisement : 01911-125712<br />
                                Office : 01904-100703
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER BOTTOM (Copyright, Developer, Social Media) --}}
        <div class="row custom-row">
            {{-- COPYRIGHT --}}
            <div class="col-md-4 custom-padding">
                <div class="footer__copyright">
                    @if(currentLocale() == 'bn')
                        <p>কপিরাইট © {{ date('Y') }} <a href="http://differentcoder.com">DifferentCoder</a></p>
                    @else
                        <p>Copyright © {{ date('Y') }} <a href="http://differentcoder.com">DifferentCoder</a></p>
                    @endif
                </div>
            </div>

            {{-- DEVELOPER CREDIT --}}
            <div class="col-md-4 custom-padding">
                <div class="footer__author">
                    @if(currentLocale() == 'bn')
                        <p>
                            ডিজাইন ও উন্নয়ন
                            <a target="_blank" href="http://differentcoder.com" class="underline">DifferentCoder</a>
                        </p>
                    @else
                        <p>
                            Design &amp; Developed By
                            <a target="_blank" href="http://differentcoder.com" class="underline">DifferentCoder</a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- SOCIAL MEDIA LINKS (from WebsiteSetting) --}}
            <div class="col-md-4 custom-padding">
                <ul class="footer__social">
                    @if(!empty($websiteSettings->facebook_url))
                        <li>
                            <a href="{{ $websiteSettings->facebook_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                                    <path d="M279.14 288l14.22-92.66h-88.91V142.41c0-25.35 12.42-50.06 52.24-50.06H293V6.26S268.43 0 243.87 0c-73.22 0-121.15 44.38-121.15 124.72v70.62H48v92.66h74.72V512h91.86V288z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->twitter_url))
                        <li>
                            <a href="{{ $websiteSettings->twitter_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->youtube_url))
                        <li>
                            <a href="{{ $websiteSettings->youtube_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                    <path d="M549.655 124.083c-6.281-23.622-24.737-42.078-48.359-48.359C457.798 64 288 64 288 64S118.202 64 74.704 75.724c-23.622 6.281-42.078 24.737-48.359 48.359C14.621 167.202 14.621 256 14.621 256s0 88.798 11.724 131.917c6.281 23.622 24.737 42.078 48.359 48.359C118.202 448 288 448 288 448s169.798 0 213.296-11.724c23.622-6.281 42.078-24.737 48.359-48.359C561.379 344.798 561.379 256 561.379 256s0-88.798-11.724-131.917zM232 336V176l144 80-144 80z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->linkedin_url))
                        <li>
                            <a href="{{ $websiteSettings->linkedin_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->whatsapp_url))
                        <li>
                            <a href="{{ $websiteSettings->whatsapp_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif

                    @if(!empty($websiteSettings->rss_url))
                        <li>
                            <a href="{{ $websiteSettings->rss_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="RSS Feed">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                    <path d="M0 64C0 46.3 14.3 32 32 32c229.8 0 416 186.2 416 416c0 17.7-14.3 32-32 32s-32-14.3-32-32C384 253.6 226.4 96 32 96C14.3 96 0 81.7 0 64zM0 416a64 64 0 1 1 128 0A64 64 0 1 1 0 416zM32 160c159.1 0 288 128.9 288 288c0 17.7-14.3 32-32 32s-32-14.3-32-32c0-123.7-100.3-224-224-224c-17.7 0-32-14.3-32-32s14.3-32 32-32z"></path>
                                </svg>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</footer>
