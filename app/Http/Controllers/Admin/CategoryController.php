<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::with('parent')
                ->select('categories.*')
                ->withCount('posts')
                ->orderBy('order');

            // Apply status filter ONLY if it has a value
            if ($request->filled('status')) {
                $query->where('is_active', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('name', function ($category) {
                    return $category->name_en ?? $category->name_bn;
                })
                ->addColumn('parent', function ($category) {
                    if ($category->parent) {
                        return $category->parent->name_en ?? $category->parent->name_bn;
                    }
                    return '—';
                })
                ->addColumn('level', function ($category) {
                    return $category->getLevel();
                })
                ->rawColumns(['name', 'parent'])
                ->make(true);
        }

        return view('admin.categories.index');
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->orderBy('order')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Custom validation: at least one language required
        if (empty($request->name_en) && empty($request->name_bn)) {
            return redirect()->back()->withErrors([
                'name_en' => 'Either English or Bangla name is required.',
                'name_bn' => 'Either English or Bangla name is required.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name_en' => 'nullable|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_bn' => 'nullable|string',
            'meta_keywords_en' => 'nullable|string',
            'meta_keywords_bn' => 'nullable|string',
        ]);

        // Handle checkboxes
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['show_in_menu'] = $request->has('show_in_menu') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('order')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        // Custom validation: at least one language required
        if (empty($request->name_en) && empty($request->name_bn)) {
            return redirect()->back()->withErrors([
                'name_en' => 'Either English or Bangla name is required.',
                'name_bn' => 'Either English or Bangla name is required.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name_en' => 'nullable|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'show_in_menu' => 'nullable|boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_bn' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string',
            'meta_description_bn' => 'nullable|string',
            'meta_keywords_en' => 'nullable|string',
            'meta_keywords_bn' => 'nullable|string',
        ]);

        // Handle checkboxes
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['show_in_menu'] = $request->has('show_in_menu') ? 1 : 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with ' . $category->posts()->count() . ' posts'
            ], 400);
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with sub-categories'
            ], 400);
        }

        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No categories selected'
            ], 400);
        }

        $deleted = 0;
        $skipped = 0;
        $reasons = [];

        foreach ($ids as $id) {
            $category = Category::find($id);
            
            if (!$category) {
                $skipped++;
                continue;
            }

            // Skip if has posts
            if ($category->posts()->count() > 0) {
                $skipped++;
                $reasons[] = ($category->name_en ?? $category->name_bn) . ' (has posts)';
                continue;
            }

            // Skip if has children
            if ($category->children()->count() > 0) {
                $skipped++;
                $reasons[] = ($category->name_en ?? $category->name_bn) . ' (has sub-categories)';
                continue;
            }

            // Delete image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();
            $deleted++;
        }

        $message = "Deleted: {$deleted} category(ies)";
        if ($skipped > 0) {
            $message .= ", Skipped: {$skipped} category(ies)";
            if (!empty($reasons)) {
                $message .= " (" . implode(', ', array_slice($reasons, 0, 3)) . ")";
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // Get subcategories via AJAX
    public function getSubcategories($parentId)
    {
        $subcategories = Category::where('parent_id', $parentId)
            ->where('is_active', 1)
            ->orderBy('order')
            ->get(['id', 'name_en', 'name_bn']);

        return response()->json($subcategories);
    }
}
