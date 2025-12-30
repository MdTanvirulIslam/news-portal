<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request)
    {
        // If AJAX request (DataTables), return JSON
        if ($request->ajax()) {
            $query = Page::select(['id', 'title_en', 'title_bn', 'is_active', 'created_at']);

            // Handle search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('title_en', 'like', "%{$search}%")
                        ->orWhere('title_bn', 'like', "%{$search}%");
                });
            }

            // Total records
            $totalRecords = Page::count();
            $filteredRecords = $query->count();

            // Handle ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                $columns = ['id', 'title_en', 'title_bn', 'is_active', 'created_at'];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Handle pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $pages = $query->skip($start)->take($length)->get();

            // Format data for DataTables
            $data = [];
            foreach ($pages as $index => $page) {
                $checked = $page->is_active ? 'checked' : '';

                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'title_en' => $page->title_en ?? '-',
                    'title_bn' => $page->title_bn ?? '-',
                    'status' => '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" ' . $checked . '
                               onchange="toggleStatus(' . $page->id . ')">
                    </div>',
                    'created' => $page->created_at->format('M d, Y'),
                    'action' => '<div class="action-btns d-flex gap-2 justify-content-center" role="group">
                        <a href="' . route('admin.pages.edit', $page->id) . '"
                           class="btn btn-sm btn-icon btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="deletePage(' . $page->id . ')"
                                class="btn btn-sm btn-icon btn-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>'
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        }

        // Regular page request, return view
        return view('admin.pages.index');
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'slug_en' => 'nullable|string|max:255|unique:pages,slug_en',
            'slug_bn' => 'nullable|string|max:255|unique:pages,slug_bn',
            'content_en' => 'required|string',
            'content_bn' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_bn' => 'nullable|string|max:500',
            'meta_keywords_en' => 'nullable|string|max:255',
            'meta_keywords_bn' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slugs if not provided
        if (empty($validated['slug_en'])) {
            $validated['slug_en'] = Str::slug($validated['title_en']);
        }
        if (empty($validated['slug_bn'])) {
            $validated['slug_bn'] = Str::slug($validated['title_bn']);
        }

        // Handle checkboxes
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['show_in_menu'] = $request->has('show_in_menu') ? 1 : 0;
        $validated['order'] = $request->input('order', 0);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the page
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'required|string|max:255',
            'slug_en' => 'nullable|string|max:255|unique:pages,slug_en,' . $page->id,
            'slug_bn' => 'nullable|string|max:255|unique:pages,slug_bn,' . $page->id,
            'content_en' => 'required|string',
            'content_bn' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_image' => 'nullable|boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_bn' => 'nullable|string|max:500',
            'meta_keywords_en' => 'nullable|string|max:255',
            'meta_keywords_bn' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
        ]);

        // Auto-generate slugs if not provided
        if (empty($validated['slug_en'])) {
            $validated['slug_en'] = Str::slug($validated['title_en']);
        }
        if (empty($validated['slug_bn'])) {
            $validated['slug_bn'] = Str::slug($validated['title_bn']);
        }

        // Handle checkboxes
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['show_in_menu'] = $request->has('show_in_menu') ? 1 : 0;
        $validated['order'] = $request->input('order', 0);

        // Handle image removal
        if ($request->has('remove_image') && $page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
            $validated['featured_image'] = null;
        }

        // Handle new featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page)
    {
        // Delete featured image if exists
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete(); // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully!'
        ]);
    }

    /**
     * Toggle page status (active/inactive)
     */
    public function toggleStatus(Page $page)
    {
        $page->is_active = !$page->is_active;
        $page->save();

        $status = $page->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'is_active' => $page->is_active,
            'message' => "Page {$status} successfully!"
        ]);
    }
}
