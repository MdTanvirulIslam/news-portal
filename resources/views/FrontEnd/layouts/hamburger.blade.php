<div class="megaMenu megaMenuDiv" id="hamburgerBtn">
    <div class="bgWhiteImp">
        <div class="container px-2 py-3 content_wrapper">
            <!-- DYNAMIC CATEGORIES -->
            <div class="row">
                @foreach($menuCategories as $category)
                    <div class="col-6 col-md-2">
                        <a class="subMenuTitle"
                           href="{{ localized_route('category.show', ['slug' => trans_slug($category)]) }}">
                            {{ trans_field($category, 'name') }}
                        </a>
                    </div>

                    <!-- SUBCATEGORIES -->
                    @foreach($category->children as $subCategory)
                        @if($subCategory->is_active && $subCategory->show_in_menu)
                            <div class="col-6 col-md-2">
                                <a class="subMenuTitle"
                                   href="{{ localized_route('category.show', ['slug' => trans_slug($subCategory)]) }}">
                                    {{ trans_field($subCategory, 'name') }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>

            <div class="col-md-12 divider"></div>

            <!-- BOTTOM LINKS -->
            <div class="col-md-12 pt-2 pb-3">
                <div class="subMenuBottomLinks">
                    <a href="{{ localized_route('category.index') }}">
                        <i class="fa fa-table-list"></i>
                        @if(currentLocale() == 'bn')
                            সব বিভাগ
                        @else
                            All Categories
                        @endif
                    </a>
                    <a href="{{ localized_route('post.index', ['type' => 'video']) }}">
                        <i class="fa fa-film"></i>
                        @if(currentLocale() == 'bn')
                            ভিডিও
                        @else
                            Video
                        @endif
                    </a>
                    <a href="{{ localized_route('post.index') }}">
                        <i class="fa fa-archive"></i>
                        @if(currentLocale() == 'bn')
                            আর্কাইভ
                        @else
                            Archive
                        @endif
                    </a>
                    <a href="{{ route('sitemap.html') }}" target="_blank">
                        <i class="fa fa-newspaper"></i>
                        @if(currentLocale() == 'bn')
                            সাইটম্যাপ
                        @else
                            Sitemap
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


