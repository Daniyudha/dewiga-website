{{--
=======================================
 AI CHAT ASSISTANT — DATA & CONFIG
=======================================
 Load this BEFORE ai-chat.blade.php
=======================================
--}}
@php
    $chatLocale = app()->getLocale();
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

    $chatActivities = \App\Models\Activity::where('is_featured', true)->orWhere('is_featured', false)->orderBy('title_id')->get();
    $chatActivitiesData = $chatActivities->map(function($a) {
        return [
            'id' => $a->id,
            'slug' => $a->slug ?? strval($a->id),
            'title' => $a->title,
            'description' => \Illuminate\Support\Str::limit(strip_tags($a->description), 120),
            'duration' => $a->duration,
            'min_pax' => $a->min_pax,
            'image' => $a->image && file_exists(storage_path('app/public/' . $a->image)) ? asset('storage/' . $a->image) : null,
            'url' => route('activities.show', $a->slug ?? $a->id),
        ];
    })->values();
@endphp
<script>
window.chatPackages = @json($chatPackagesData);
window.chatActivities = @json($chatActivitiesData);
window.chatLocale = '{{ $chatLocale }}';
</script>