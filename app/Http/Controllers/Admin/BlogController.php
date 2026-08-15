<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\BlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Auto-publish scheduled blogs whose published_at time has passed
        Blog::where('status', Blog::STATUS_SCHEDULED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->update(['status' => Blog::STATUS_PUBLISHED]);

        $query = Blog::with('category', 'user');

        // Filter by status
        if ($request->status && in_array($request->status, ['draft', 'scheduled', 'published'])) {
            $query->where('status', $request->status);
        }

        $blogs = $query->latest()->paginate(10);

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get(['name', 'name_id', 'name_en', 'id']);

        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        if($request->validated()) {
            $image = $request->file('image')->store(
                'blog/images', 'public'
            );
            $slug = Str::slug($request->title_en ?? $request->title, '-');
            $uniqueSlug = $this->makeUniqueSlug($slug);

            $status = $request->status ?? Blog::STATUS_DRAFT;
            $publishedAt = $request->published_at ?? now();

            if ($status === Blog::STATUS_SCHEDULED && $publishedAt) {
                $status = Blog::STATUS_SCHEDULED;
            } elseif ($status === Blog::STATUS_PUBLISHED) {
                $status = Blog::STATUS_PUBLISHED;
                $publishedAt = $request->published_at ?? now();
            } else {
                $status = Blog::STATUS_DRAFT;
                $publishedAt = null;
            }

            Blog::create($request->except(['image', 'status', 'published_at']) + [
                'slug' => $uniqueSlug,
                'image' => $image,
                'user_id' => auth()->id(),
                'status' => $status,
                'published_at' => $publishedAt,
            ]);
        }

        return redirect()->route('admin.blogs.index')->with([
            'message' => 'Success Created !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $categories = Category::get(['name', 'name_id', 'name_en', 'id']);

        return view('admin.blogs.edit', compact('blog','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        if($request->validated()) {
            $slug = Str::slug($request->title_en ?? $request->title, '-');
            $uniqueSlug = $this->makeUniqueSlug($slug, $blog->id);

            $status = $request->status ?? $blog->status;
            $publishedAt = $request->published_at ?? $blog->published_at;

            if ($status === Blog::STATUS_PUBLISHED) {
                $publishedAt = $request->published_at ?? now();
            } elseif ($status === Blog::STATUS_SCHEDULED && !$publishedAt) {
                $publishedAt = $request->published_at;
            } elseif ($status === Blog::STATUS_DRAFT) {
                $publishedAt = null;
            }

            if ($request->image) {
                File::delete('storage/'. $blog->image);
                $image = $request->file('image')->store(
                    'blog/images', 'public'
                );
                $blog->update($request->except(['image', 'status', 'published_at']) + [
                    'slug' => $uniqueSlug,
                    'image' => $image,
                    'status' => $status,
                    'published_at' => $publishedAt,
                ]);
            } else {
                $blog->update($request->validated() + [
                    'slug' => $uniqueSlug,
                    'status' => $status,
                    'published_at' => $publishedAt,
                ]);
            }
        }

        return redirect()->route('admin.blogs.index')->with([
            'message' => 'Success Updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        File::delete('storage/'. $blog->image);
        $blog->delete();

        return redirect()->back()->with([
            'message' => 'Success Deleted !',
            'alert-type' => 'danger'
        ]);
    }

    /**
     * Generate a unique slug by appending a counter if needed.
     */
    private function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $counter = 1;

        while (true) {
            $query = Blog::where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}