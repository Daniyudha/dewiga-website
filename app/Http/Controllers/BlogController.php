<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\TravelPackage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('user')
            ->published()
            ->latest('published_at');

        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $blogs = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('blogs.index', compact('blogs', 'categories'));
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_public, 404);

        $relatedBlogs = Blog::with('user')
                ->where('id','!=',$blog->id)
                ->where('category_id', $blog->category_id)
                ->published()
                ->get();
        $categories = Category::get();
        $travel_packages = TravelPackage::with('galleries')->get()->take(2);

        $blog->incrementReadCount();

        return view('blogs.show', compact('blog','travel_packages','relatedBlogs','categories'));
    }
}
