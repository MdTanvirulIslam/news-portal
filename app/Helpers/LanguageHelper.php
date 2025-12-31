<?php

// ============================================
// COMPLETE LanguageHelper.php
// Copy this entire file to: app/Helpers/LanguageHelper.php
// ============================================

if (!function_exists('currentLocale')) {
    /**
     * Get current locale
     */
    function currentLocale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('trans_field')) {
    /**
     * Get translated field value
     * Example: trans_field($category, 'title')
     * Returns: title_en or title_bn based on locale
     */
    function trans_field($model, $field)
    {
        $locale = currentLocale();
        $translatedField = $field . '_' . $locale;

        return $model->{$translatedField} ?? $model->{$field . '_bn'} ?? '';
    }
}

if (!function_exists('trans_slug')) {
    /**
     * Get translated slug
     * Example: trans_slug($post, 'slug')
     * Returns: slug_en or slug_bn based on locale
     */
    function trans_slug($model, $field = 'slug')
    {
        $locale = currentLocale();
        $translatedSlug = $field . '_' . $locale;

        return $model->{$translatedSlug} ?? $model->{$field . '_bn'} ?? '';
    }
}

if (!function_exists('localized_route')) {
    /**
     * Generate localized route URL
     * Example: localized_route('post.show', ['slug' => $post->slug_bn])
     */
    function localized_route($routeName, $parameters = [], $locale = null)
    {
        $locale = $locale ?? currentLocale();
        $parameters = array_merge(['locale' => $locale], $parameters);

        return route($routeName, $parameters);
    }
}

if (!function_exists('switchLocaleRoute')) {
    function switchLocaleRoute($newLocale, $model = null)
    {
        $currentLocale = currentLocale();
        $currentRoute = request()->route();

        if (!$currentRoute) {
            $currentUrl = request()->url();
            return str_replace('/' . $currentLocale, '/' . $newLocale, $currentUrl);
        }

        $routeName = $currentRoute->getName();
        $routeParams = $currentRoute->parameters();

        // If we have a model, get the correct slug for new locale
        if ($model) {
            $newSlug = $model->{'slug_' . $newLocale} ?? $model->{'slug_bn'};
            $routeParams['slug'] = $newSlug;
        }

        $routeParams['locale'] = $newLocale;

        try {
            return route($routeName, $routeParams);
        } catch (\Exception $e) {
            $currentUrl = request()->url();
            return str_replace('/' . $currentLocale, '/' . $newLocale, $currentUrl);
        }
    }
}

// ============================================
// ADDITIONAL HELPER FUNCTIONS
// ============================================

if (!function_exists('getPostImage')) {
    /**
     * Get post image URL with fallback to placeholder
     */
    function getPostImage($post, $field = 'featured_image')
    {
        $imagePath = $post->{$field} ?? null;

        if (empty($imagePath)) {
            return asset('FrontEnd/images/placeholder.jpg');
        }

        return asset('storage/' . $imagePath);
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text with proper Bangla character support
     */
    function truncateText($text, $limit = 100, $end = '...')
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit) . $end;
    }
}

if (!function_exists('formatBanglaDate')) {
    /**
     * Format date in Bangla or English based on locale
     */
    function formatBanglaDate($date, $format = 'd F Y')
    {
        $locale = currentLocale();
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        if ($locale == 'bn') {
            return $carbonDate->locale('bn')->translatedFormat($format);
        }

        return $carbonDate->format($format);
    }
}

if (!function_exists('getPostUrl')) {
    /**
     * Get full URL for a post (language-aware)
     */
    function getPostUrl($post)
    {
        $locale = currentLocale();
        $slug = trans_slug($post);

        return route('post.show', ['locale' => $locale, 'slug' => $slug]);
    }
}

if (!function_exists('getCategoryUrl')) {
    /**
     * Get full URL for a category (language-aware)
     */
    function getCategoryUrl($category)
    {
        $locale = currentLocale();
        $slug = trans_slug($category);

        return route('category.show', ['locale' => $locale, 'slug' => $slug]);
    }
}

if (!function_exists('getTagUrl')) {
    /**
     * Get full URL for a tag (language-aware)
     */
    function getTagUrl($tag)
    {
        $locale = currentLocale();
        $slug = trans_slug($tag);

        return route('tag.show', ['locale' => $locale, 'slug' => $slug]);
    }
}

if (!function_exists('shareUrl')) {
    /**
     * Generate social media share URLs
     */
    function shareUrl($platform, $url, $title = '')
    {
        $encodedUrl = urlencode($url);
        $encodedTitle = urlencode($title);

        switch ($platform) {
            case 'facebook':
                return "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}";
            case 'twitter':
                return "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}";
            case 'linkedin':
                return "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}";
            case 'whatsapp':
                return "https://api.whatsapp.com/send?text={$encodedTitle}%20{$encodedUrl}";
            default:
                return '#';
        }
    }
}

if (!function_exists('getReadingTime')) {
    /**
     * Calculate reading time in minutes
     */
    function getReadingTime($content, $wordsPerMinute = 200)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / $wordsPerMinute);

        return max(1, $minutes);
    }
}

if (!function_exists('getUserProfileUrl')) {
    function getUserProfileUrl($user)
    {
        $locale = currentLocale();
        $userId = is_object($user) ? $user->id : $user;

        // Change from /user/ to /user-profile/
        return url("/{$locale}/user/{$userId}");
    }
}

if (!function_exists('getUserAvatar')) {
    /**
     * Get user avatar URL (profile picture or gravatar fallback)
     *
     * @param object $user The user object
     * @param int $size Avatar size in pixels (default: 200)
     * @return string
     */
    function getUserAvatar($user, $size = 200)
    {
        // Check if user has uploaded profile picture
        if (!empty($user->profile_picture)) {
            return asset('storage/profiles/' . $user->profile_picture);
        }

        // Gravatar fallback based on email
        $hash = md5(strtolower(trim($user->email)));
        return "https://secure.gravatar.com/avatar/{$hash}?s={$size}&d=mm&r=g";
    }
}

if (!function_exists('getUserRoleName')) {
    /**
     * Get user role name in current language
     *
     * @param object $user The user object
     * @return string
     */
    function getUserRoleName($user)
    {
        $locale = currentLocale();

        if ($locale === 'bn') {
            $roles = [
                'admin' => 'প্রশাসক',
                'editor' => 'সম্পাদক',
                'reporter' => 'প্রতিবেদক',
                'contributor' => 'অবদানকারী',
            ];
        } else {
            $roles = [
                'admin' => 'Admin',
                'editor' => 'Editor',
                'reporter' => 'Reporter',
                'contributor' => 'Contributor',
            ];
        }

        return $roles[$user->role] ?? ucfirst($user->role);
    }
}

if (!function_exists('getUserPostCount')) {
    /**
     * Get user's published post count
     *
     * @param object|int $user The user object or user ID
     * @return int
     */
    function getUserPostCount($user)
    {
        $userId = is_object($user) ? $user->id : $user;

        return \App\Models\Post::where('user_id', $userId)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->count();
    }
}

if (!function_exists('formatUserRole')) {
    /**
     * Format user role with icon and name
     *
     * @param object $user The user object
     * @return string HTML string with icon and role name
     */
    function formatUserRole($user)
    {
        $icons = [
            'admin' => 'fa-shield-alt',
            'editor' => 'fa-pen',
            'reporter' => 'fa-newspaper',
            'contributor' => 'fa-user-edit',
        ];

        $icon = $icons[$user->role] ?? 'fa-user';
        $roleName = getUserRoleName($user);

        return '<i class="fa-solid ' . $icon . '"></i> ' . $roleName;
    }
}


// Alternative using route() method
if (!function_exists('getUserProfileUrlAlt')) {
    /**
     * Alternative: Get user profile URL using route helper
     *
     * @param object|int $user The user object or user ID
     * @return string
     */
    function getUserProfileUrlAlt($user)
    {
        $userId = is_object($user) ? $user->id : $user;

        return route('user.profile', ['id' => $userId]);
    }
}
