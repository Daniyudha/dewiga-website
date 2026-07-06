<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function destroy(Testimonial $testimonial)
    {
        // Delete avatar if exists
        if ($testimonial->avatar && file_exists(public_path('storage/' . $testimonial->avatar))) {
            unlink(public_path('storage/' . $testimonial->avatar));
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('message', __('Testimonial deleted successfully.'))
            ->with('alert-type', 'success');
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_active' => !$testimonial->is_active,
        ]);

        $status = $testimonial->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.testimonials.index')
            ->with('message', __('Testimonial :status successfully.', ['status' => $status]))
            ->with('alert-type', 'success');
    }
}
