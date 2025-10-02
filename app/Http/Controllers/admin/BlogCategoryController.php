<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Http\Requests\BlogCategory\StoreBlogCategoryRequest;
use App\Http\Requests\BlogCategory\UpdateBlogCategoryRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('created_at', 'desc')->paginate(8);
        return view('admin.pages.blog_categories.list', compact('categories'));
    }


    public function store(StoreBlogCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        // $data['lang'] = $data['lang'] ?? app()->getLocale();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog_categories', 'public');
        }

        $category = BlogCategory::create($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Category created successfully!',
            'category' => $category
        ]);

    }
    public function show(BlogCategory $blog_category)
    {
        return response()->json($blog_category);
    }


    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blog_category)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($blog_category->image && Storage::disk('public')->exists($blog_category->image)) {
                Storage::disk('public')->delete($blog_category->image);
            }
            $data['image'] = $request->file('image')->store('blog_categories', 'public');
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $blog_category->update($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Category updated successfully!',
            'category' => $blog_category
        ]);

    }

    public function destroy(BlogCategory $blog_category)
    {
        if ($blog_category->image && Storage::disk('public')->exists($blog_category->image)) {
            Storage::disk('public')->delete($blog_category->image);
        }

        $blog_category->delete();

       return response()->json([
            'success'  => true,
            'message'  => 'Category deleted successfully!',
            'category' => $blog_category
        ]);

    }
}
