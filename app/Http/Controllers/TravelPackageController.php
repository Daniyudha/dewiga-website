<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelPackage;

class TravelPackageController extends Controller
{
    public function index()
    {
        $travel_packages = TravelPackage::with('galleries')->paginate(9);

        return view('travel_packages.index', compact('travel_packages'));
    }

    public function show(TravelPackage $travel_package)
    {
        $travel_packages = TravelPackage::where('id', '!=', $travel_package->id)->get();

        return view('travel_packages.show', compact('travel_package', 'travel_packages'));
    }

    public function book(TravelPackage $travel_package)
    {
        return view('bookings.create', compact('travel_package'));
    }
}
