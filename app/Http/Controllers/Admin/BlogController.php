<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('blogCategory')->latest()->paginate(6);
        return view('admin.pages.blogs.list', compact('blogs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'name'            => 'required|string|max:255',
            'content'         => 'required|string',
            'tags'            => 'nullable|string',
            'keywords'        => 'nullable|string',
            'meta_description'=> 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('blogs', $filename, 'public');
            $data['image'] = $path;
        }

        $blog = Blog::create($data)->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully.',
            'blog'    => $blog
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'blog_category_id' => 'sometimes|exists:blog_categories,id',
            'name'            => 'sometimes|string|max:255',
            'content'         => 'sometimes|string',
            'tags'            => 'nullable|string',
            'keywords'        => 'nullable|string',
            'meta_description'=> 'nullable|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('blogs', $filename, 'public');
            $data['image'] = $path;
        }

        $blog->update($data);
        $blog->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully.',
            'blog'    => $blog
        ]);
    }

    public function show(Blog $blog)
    {
        $blog->load('blogCategory');
        return view('admin.pages.blogs.show', compact('blog'));
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully.'
        ]);
    }
}
