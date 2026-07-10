{{-- 
=======================================
 AI CHAT ASSISTANT — DATA & CONFIG
=======================================
 Load this BEFORE ai-chat.blade.php
=======================================
--}}
@php 
    $chatPackages = \App\Models\TravelPackage::with('galleries')->get();
    $chatPackagesData = $chatPackages->map(function($p) {
        $imageUrl = null;
        $firstGallery = $p->galleries->first();
        if ($firstGallery && $firstGallery->images) {
            $storagePath = storage_path('app/public/' . $firstGallery->images);
            if (file_exists($storagePath)) {
                $imageUrl = asset('storage/' . $firstGallery->images);
            }
        }
        return [
            'id' => $p->id,
            'slug' => $p->slug ?? strval($p->id),
            'type' => $p->type,
            'price' => number_format($p->price, 0, ',', '.'),
            'description' => \Illuminate\Support\Str::limit($p->description, 100),
            'image' => $imageUrl,
            'url' => route('travel_package.show', $p->slug ?? $p->id),
        ];
    })->values();
@endphp
<script>window.chatPackages = @json($chatPackagesData);</script>
