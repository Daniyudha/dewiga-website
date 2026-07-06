<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\TravelPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $travel_packages = TravelPackage::with('galleries')->get();
        $blogs = Blog::latest()->take(3)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->get();

        return view('homepage', compact('travel_packages', 'blogs', 'testimonials'));
    }

    public function sitemap()
    {
        $travel_packages = TravelPackage::all();
        $blogs = Blog::all();

        return response()->view('sitemap', compact('travel_packages', 'blogs'))->header('Content-Type', 'text/xml');
    }
}
