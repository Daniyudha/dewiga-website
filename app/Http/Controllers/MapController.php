<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Homestay;
use App\Models\Schedule;

class MapController extends Controller
{
    /**
     * Peta penuh: semua homestay dan kegiatan yang aktif.
     */
    public function index()
    {
        $homestays = Homestay::active()->ordered()->get();
        $activities = Activity::active()->ordered()->get();

        $markers = $this->buildMarkers($homestays, $activities);

        return view('map.index', compact('homestays', 'activities', 'markers'));
    }

    /**
     * Peta untuk event tertentu: hanya homestay dan kegiatan
     * yang diaktifkan untuk event tersebut, dengan penempatan kelompok.
     */
    public function show(Schedule $schedule)
    {
        $homestays = $schedule->homestays()->active()->ordered()->get();
        $activities = $schedule->activities()->active()->ordered()->get();

        $markers = [];
        foreach ($homestays as $h) {
            $pivot = $h->pivot;
            $locale = app()->getLocale();
            $groupName = $locale === 'en'
                ? ($pivot->group_name_en ?? $pivot->group_name_id)
                : ($pivot->group_name_id);

            if ($h->latitude && $h->longitude) {
                $markers[] = [
                    'type' => 'homestay',
                    'lat' => (float) $h->latitude,
                    'lng' => (float) $h->longitude,
                    'title' => $h->name,
                    'popup' => '<strong>' . e($h->name) . '</strong><br>'
                        . e($h->address)
                        . ($groupName ? '<br><strong>' . __('Occupied by') . ':</strong> ' . e($groupName) : '')
                        . '<br><small>' . __('Homestay') . '</small>',
                ];
            }
        }

        foreach ($activities as $a) {
            if ($a->latitude && $a->longitude) {
                $markers[] = [
                    'type' => 'activity',
                    'lat' => (float) $a->latitude,
                    'lng' => (float) $a->longitude,
                    'title' => $a->title,
                    'popup' => '<strong>' . e($a->title) . '</strong><br><small>' . __('Activity') . '</small>',
                ];
            }
        }

        return view('map.show', compact('schedule', 'homestays', 'activities', 'markers'));
    }

    /**
     * Susun array marker untuk homestay dan aktivitas yang memiliki koordinat.
     */
    protected function buildMarkers($homestays, $activities): array
    {
        $markers = [];

        foreach ($homestays as $h) {
            if ($h->latitude && $h->longitude) {
                $markers[] = [
                    'type' => 'homestay',
                    'lat' => (float) $h->latitude,
                    'lng' => (float) $h->longitude,
                    'title' => $h->name,
                    'popup' => '<strong>' . e($h->name) . '</strong><br>'
                        . e($h->address)
                        . '<br><small>' . __('Homestay') . '</small>',
                ];
            }
        }

        foreach ($activities as $a) {
            if ($a->latitude && $a->longitude) {
                $markers[] = [
                    'type' => 'activity',
                    'lat' => (float) $a->latitude,
                    'lng' => (float) $a->longitude,
                    'title' => $a->title,
                    'popup' => '<strong>' . e($a->title) . '</strong><br><small>' . __('Activity') . '</small>',
                ];
            }
        }

        return $markers;
    }
}
