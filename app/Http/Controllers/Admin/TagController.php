<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    /**
     * Display a listing of tags
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Tag::select([
                'id',
                'name_en',
                'name_bn',
                'slug',
                'is_active',
                'created_at'
            ])->withCount('posts')->orderBy('created_at', 'desc');

            // Apply status filter ONLY if it has a value
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            return DataTables::of($query)->make(true);
        }

        return view('admin.tags.index');
    }

    /**
     * Show the form for creating a new tag
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag
     */
    public function store(Request $request)
    {
        // At least one name (English OR Bangla) must be provided
        if (empty($request->name_en) && empty($request->name_bn)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'name_en' => 'Either English or Bangla name is required.',
                    'name_bn' => 'Either English or Bangla name is required.',
                ]);
        }

        $validated = $request->validate([
            'name_en' => 'nullable|string|max:255|unique:tags,name_en',
            'name_bn' => 'nullable|string|max:255|unique:tags,name_bn',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_bn' => 'nullable|string',
            'meta_keywords_en' => 'nullable|string',
            'meta_keywords_bn' => 'nullable|string',
        ]);

        // Set default for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        // Generate slug from whichever name is provided
        if (empty($validated['slug'])) {
            if (!empty($validated['name_en'])) {
                $validated['slug'] = \Str::slug($validated['name_en']);
            } elseif (!empty($validated['name_bn'])) {
                $validated['slug'] = \Str::slug($validated['name_bn']);
            }
        }

        Tag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully');
    }

    /**
     * Show the form for editing the specified tag
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag
     */
    public function update(Request $request, Tag $tag)
    {
        // At least one name (English OR Bangla) must be provided
        if (empty($request->name_en) && empty($request->name_bn)) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'name_en' => 'Either English or Bangla name is required.',
                    'name_bn' => 'Either English or Bangla name is required.',
                ]);
        }

        $validated = $request->validate([
            'name_en' => 'nullable|string|max:255|unique:tags,name_en,' . $tag->id,
            'name_bn' => 'nullable|string|max:255|unique:tags,name_bn,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_bn' => 'nullable|string',
            'meta_keywords_en' => 'nullable|string',
            'meta_keywords_bn' => 'nullable|string',
        ]);

        // Set default for is_active if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully');
    }

    /**
     * Remove the specified tag
     */
    public function destroy(Tag $tag)
    {
        if ($tag->posts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete tag with ' . $tag->posts()->count() . ' associated posts'
            ], 400);
        }

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully'
        ]);
    }

    /**
     * Bulk delete tags
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tags,id'
        ]);

        $tags = Tag::whereIn('id', $request->ids)->get();

        $deleted = 0;
        $skipped = 0;

        foreach ($tags as $tag) {
            if ($tag->posts()->count() > 0) {
                $skipped++;
                continue;
            }
            $tag->delete();
            $deleted++;
        }

        $message = "Deleted {$deleted} tag(s)";
        if ($skipped > 0) {
            $message .= ". Skipped {$skipped} tag(s) with posts.";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
