<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('blogCategory')->orderBy('created_at', 'desc')->paginate(8);
        $categories = BlogCategory::get();

        return view('admin.pages.blogs.list', compact('blogs', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            // 'slug' => 'required|string|max:255',
            'tags.*' => 'string|distinct',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string|distinct',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $validated['tags'] = isset($validated['tags']) ? array_map('trim', $validated['tags']) : [];
        $validated['keywords'] = isset($validated['keywords']) ? array_map('trim', $validated['keywords']) : [];
        $validated['slug'] = Str::slug($validated['name']);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image'] = $path;
        }

        $blog = Blog::create($validated);

        return response()->json([
            'success' => true,
            'blog' => $blog,
            'category_name' => $blog->blogCategory->name ?? ''
        ]);
    }

    public function edit(Blog $blog)
    {
        try {
            function toArrayOrEmpty($val) {
                if (is_array($val)) return $val;
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    if (is_array($decoded)) return $decoded;
                }
                return [];
            }

            return response()->json([
                'success' => true,
                'blog' => [
                    'id' => $blog->id,
                    'blog_category_id' => $blog->blog_category_id,
                    'name' => $blog->name,
                    // 'slug' => $blog->slug,
                    'content' => $blog->content ?? '',
                    'meta_description' => $blog->meta_description ?? '',
                    'tags' => toArrayOrEmpty($blog->tags),
                    'keywords' => toArrayOrEmpty($blog->keywords),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في جلب البيانات: ' . $e->getMessage()
            ], 500);
        }
    }
public function update(Request $request, Blog $blog)
{
    try {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|distinct',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string|distinct',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['tags'] = $validated['tags'] ?? [];
        $validated['keywords'] = $validated['keywords'] ?? [];

        if ($request->hasFile('image')) {
            if ($blog->image && \Storage::disk('public')->exists($blog->image)) {
                \Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs','public');
        }

        $blog->update($validated);
        $blog->load('blogCategory');

        return response()->json(['success'=>true,'blog'=>$blog]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    }
}


    public function destroy(Blog $blog)
    {
        try {
            // Delete image if exists
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المدونة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الحذف: ' . $e->getMessage()
            ], 500);
        }
    }
}
