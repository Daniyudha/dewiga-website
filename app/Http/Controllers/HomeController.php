<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\HeroSetting;
use App\Models\Testimonial;
use App\Models\TravelPackage;
use App\Models\PartnerLogo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $travel_packages = TravelPackage::with('galleries')->get();
        $blogs = Blog::latest()->take(3)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->get();
        $partnerLogos = PartnerLogo::active()->ordered()->get();
        $heroSetting = HeroSetting::getForPage('home');

        return view('homepage', compact('travel_packages', 'blogs', 'testimonials', 'partnerLogos', 'heroSetting'));
    }

    public function sitemap()
    {
        $travel_packages = TravelPackage::all();
        $blogs = Blog::all();

        return response()->view('sitemap', compact('travel_packages', 'blogs'))->header('Content-Type', 'text/xml');
    }
}
