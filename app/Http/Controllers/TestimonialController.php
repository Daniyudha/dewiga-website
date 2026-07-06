<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TestimonialController extends Controller
{
    public function create()
    {
        return view('testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'locale'  => 'required|in:id,en',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $locale = $validated['locale'];
        $content = $validated['content'];

        // Build bilingual data
        $data = [
            'name'    => $validated['name'],
            'locale'  => $locale,
        ];

        if ($locale === 'id') {
            $data['content_id'] = $content;
            // Auto-translate to English
            $data['content_en'] = $this->translate($content, 'id', 'en');
        } else {
            $data['content_en'] = $content;
            // Auto-translate to Indonesian
            $data['content_id'] = $this->translate($content, 'en', 'id');
        }

        // Also set the original content column for backward compatibility
        $data['content'] = $content;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('testimonials.create')
            ->with('message', __('Thank you! Your testimonial has been submitted and is awaiting approval.'))
            ->with('alert-type', 'success');
    }

    /**
     * Auto-translate text using Google Translate (free, no API key required)
     */
    private function translate(string $text, string $from, string $to): string
    {
        try {
            $tr = new GoogleTranslate();
            $tr->setSource($from);
            $tr->setTarget($to);
            $result = $tr->translate($text);

            return $result ?: $text;
        } catch (\Exception $e) {
            // Fallback to original text if translation fails
            return $text;
        }
    }
}
