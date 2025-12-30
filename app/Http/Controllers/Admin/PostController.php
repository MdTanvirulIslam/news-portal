<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                $userRole = $user->role;

                $query = Post::with(['user', 'categories']);

                // Role-based filtering
                if (in_array($userRole, ['reporter', 'contributor'])) {
                    $query->where('user_id', $user->id);
                }

                // Apply filters
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('post_type')) {
                    $query->where('post_type', $request->post_type);
                }

                if ($request->filled('category_id')) {
                    $query->whereHas('categories', function($q) use ($request) {
                        $q->where('categories.id', $request->category_id);
                    });
                }

                if ($request->filled('is_featured') && $request->is_featured === 'true') {
                    $query->where('is_featured', true);
                }

                if ($request->filled('is_breaking') && $request->is_breaking === 'true') {
                    $query->where('is_breaking', true);
                }

                // Search
                if ($request->filled('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('title_en', 'like', "%{$search}%")
                            ->orWhere('title_bn', 'like', "%{$search}%");
                    });
                }

                $totalRecords = Post::count();
                $filteredRecords = $query->count();

                // Sorting
                $orderColumn = $request->order[0]['column'] ?? 0;
                $orderDir = $request->order[0]['dir'] ?? 'desc';

                switch($orderColumn) {
                    case 0: $query->orderBy('id', $orderDir); break;
                    case 1: $query->orderBy('title_en', $orderDir); break;
                    case 4: $query->orderBy('status', $orderDir); break;
                    case 6: $query->orderBy('views_count', $orderDir); break;
                    case 7: $query->orderBy('published_at', $orderDir); break;
                    default: $query->orderBy('id', $orderDir);
                }

                // Pagination
                $start = $request->start ?? 0;
                $length = $request->length ?? 10;
                $posts = $query->skip($start)->take($length)->get();

                $data = $posts->map(function($post) use ($userRole) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title_en ?: $post->title_bn ?: 'Untitled',
                        'category' => $post->categories->first()->name_en ?? 'Uncategorized',
                        'author' => $post->user->name ?? 'Unknown',
                        'status' => $post->status,
                        'type' => $post->post_type,
                        'is_featured' => (bool)$post->is_featured,
                        'is_breaking' => (bool)$post->is_breaking,
                        'views' => $post->views_count ?? 0,
                        'published_at' => $post->published_at ? $post->published_at->format('Y-m-d H:i') : null,
                        'edit_url' => route('admin.posts.edit', $post->id),
                        'delete_url' => route('admin.posts.destroy', $post->id),
                        'approve_url' => route('admin.posts.approve', $post->id),
                        'can_approve' => in_array($userRole, ['admin', 'editor']) && $post->status === 'pending',
                    ];
                });

                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'data' => $data
                ]);

            } catch (\Exception $e) {
                Log::error('Posts AJAX error: ' . $e->getMessage());
                return response()->json([
                    'draw' => intval($request->draw),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage()
                ]);
            }
        }

        $categories = Category::where('is_active', true)->get();
        return view('admin.posts.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $tags = Tag::where('is_active', true)->get();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $postType = $request->post_type;

        // Title validation (at least one required)
        if (empty($request->title_en) && empty($request->title_bn)) {
            return back()->withErrors(['title' => 'At least one title (English or Bangla) is required.'])->withInput();
        }

        // Type-specific validation
        $rules = $this->getValidationRules($postType, 'create');
        $validated = $request->validate($rules);

        // Prepare data
        $data = $validated;
        $user = Auth::user();
        $data['user_id'] = $user->id;

        // Status handling for reporters/contributors
        if (in_array($user->role, ['reporter', 'contributor'])) {
            if (!in_array($data['status'], ['draft', 'pending'])) {
                $data['status'] = 'pending';
            }
        }

        // Handle checkboxes
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_breaking'] = $request->has('is_breaking') ? 1 : 0;
        $data['allow_comments'] = $request->has('allow_comments') ? 1 : 0;

        // Handle published_at
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'scheduled' && !empty($data['scheduled_at'])) {
            $data['published_at'] = $data['scheduled_at'];
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Handle audio file (for articles only)
        if ($request->hasFile('audio_file') && $postType === 'article') {
            $data['audio_file'] = $request->file('audio_file')->store('posts/audio', 'public');
        }

        // Create post
        $post = Post::create($data);

        // Attach categories
        if (!empty($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        // Attach tags
        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        // Handle gallery images with captions
        if ($request->hasFile('gallery_images') && $postType === 'gallery') {
            $captionsEn = $request->input('gallery_captions_en', []);
            $captionsBn = $request->input('gallery_captions_bn', []);

            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('posts/gallery', 'public');
                $post->media()->create([
                    'media_type' => 'image',
                    'file_path' => $path,
                    'caption_en' => $captionsEn[$index] ?? null,
                    'caption_bn' => $captionsBn[$index] ?? null,
                    'order' => $index,
                    'file_size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                ]);
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $user = Auth::user();

        if (in_array($user->role, ['reporter', 'contributor']) && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized. You can only edit your own posts.');
        }

        $categories = Category::where('is_active', true)->get();
        $tags = Tag::where('is_active', true)->get();
        $post->load('categories', 'tags', 'media');

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::user();

        if (in_array($user->role, ['reporter', 'contributor']) && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized. You can only update your own posts.');
        }

        // Title validation
        if (empty($request->title_en) && empty($request->title_bn)) {
            return back()->withErrors(['title' => 'At least one title (English or Bangla) is required.'])->withInput();
        }

        // Type-specific validation
        $rules = $this->getValidationRules($post->post_type, 'edit');
        $validated = $request->validate($rules);

        $data = $validated;

        // Status handling
        if (in_array($user->role, ['reporter', 'contributor'])) {
            $data['status'] = 'pending';
        }

        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_breaking'] = $request->has('is_breaking') ? 1 : 0;
        $data['allow_comments'] = $request->has('allow_comments') ? 1 : 0;

        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        } elseif ($data['status'] === 'scheduled' && !empty($data['scheduled_at'])) {
            $data['published_at'] = $data['scheduled_at'];
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Handle audio file
        if ($request->hasFile('audio_file')) {
            if ($post->audio_file) {
                Storage::disk('public')->delete($post->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('posts/audio', 'public');
        }

        $post->update($data);

        if (!empty($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        if (!empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        // Handle new gallery images
        if ($request->hasFile('gallery_images') && $post->post_type === 'gallery') {
            $captionsEn = $request->input('gallery_captions_en', []);
            $captionsBn = $request->input('gallery_captions_bn', []);
            $lastOrder = $post->media()->max('order') ?? -1;

            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('posts/gallery', 'public');
                $post->media()->create([
                    'media_type' => 'image',
                    'file_path' => $path,
                    'caption_en' => $captionsEn[$index] ?? null,
                    'caption_bn' => $captionsBn[$index] ?? null,
                    'order' => $lastOrder + $index + 1,
                    'file_size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                ]);
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully!');
    }

    public function approve(Post $post)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'editor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Admin or Editor can approve posts.'
            ], 403);
        }

        $post->update([
            'status' => 'published',
            'published_at' => $post->published_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post approved and published successfully!'
        ]);
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();

        if (in_array($user->role, ['reporter', 'contributor']) && $post->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only delete your own posts.'
            ], 403);
        }

        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        if ($post->audio_file) {
            Storage::disk('public')->delete($post->audio_file);
        }

        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $post->delete();

        return response()->json(['success' => true, 'message' => 'Post deleted successfully!']);
    }

    /**
     * Get validation rules based on post type
     */
    private function getValidationRules($postType, $action = 'create')
    {
        $baseRules = [
            'post_type' => 'required|in:article,gallery,video',
            'title_en' => 'nullable|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => ($action === 'create' ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_bn' => 'nullable|string|max:500',
            'meta_keywords_en' => 'nullable|string|max:255',
            'meta_keywords_bn' => 'nullable|string|max:255',
            'status' => 'required|in:draft,pending,published,rejected,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
        ];

        // Type-specific rules
        if ($postType === 'article') {
            $baseRules['content_en'] = 'nullable|string';
            $baseRules['content_bn'] = 'nullable|string';
            $baseRules['excerpt_en'] = 'nullable|string|max:500';
            $baseRules['excerpt_bn'] = 'nullable|string|max:500';
            $baseRules['video_url'] = 'nullable|url';
            $baseRules['audio_file'] = 'nullable|file|mimes:mp3,wav|max:10240';
        }
        elseif ($postType === 'video') {
            $baseRules['video_url'] = 'required|url';
            $baseRules['excerpt_en'] = 'nullable|string|max:500';
            $baseRules['excerpt_bn'] = 'nullable|string|max:500';
        }
        elseif ($postType === 'gallery') {
            $baseRules['gallery_images.*'] = ($action === 'create' ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif,webp|max:5120';
            $baseRules['gallery_captions_en.*'] = 'nullable|string|max:255';
            $baseRules['gallery_captions_bn.*'] = 'nullable|string|max:255';
        }

        return $baseRules;
    }
}
