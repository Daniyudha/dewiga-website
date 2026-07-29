<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\OpenTripRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : date('Y');

        $years = $this->getAvailableYears();

        $visits = $this->getVisitData($year, $request);

        $summary = $this->getSummary($visits);

        $perPage = 20;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $visitsPaginated = new LengthAwarePaginator(
            $visits->slice($offset, $perPage)->values(),
            $visits->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.visit-reports.index', compact('visitsPaginated', 'summary', 'year', 'years'));
    }

    public function export(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : date('Y');

        $visits = $this->getVisitData($year, $request);

        $filename = "data_kunjungan_{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($visits) {
            $output = fopen('php://output', 'w');

            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($output, [
                'No',
                'Nama PIC',
                'Asal / Instansi',
                'Kontak',
                'Tipe Tamu',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Length of Stay (Hari)',
                'Jumlah Orang',
                'Tipe Kunjungan',
                'Status',
            ]);

            $no = 1;
            foreach ($visits as $visit) {
                fputcsv($output, [
                    $no++,
                    $visit->visitor_name,
                    $visit->institution ?? '-',
                    $visit->number_phone ?? '-',
                    $visit->guest_type_label ?? 'Lokal',
                    $visit->start_date ? date('d/m/Y', strtotime($visit->start_date)) : '-',
                    $visit->end_date ? date('d/m/Y', strtotime($visit->end_date)) : '-',
                    $visit->length_of_stay,
                    $visit->total_people,
                    $visit->visit_type_label,
                    $visit->status_label,
                ]);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

    private function getVisitData(int $year, Request $request)
    {
        $scheduleVisits = Schedule::query()
            ->whereYear('start_date', $year)
            ->where('is_active', true)
            ->whereIn('type', ['confirmed', 'pending'])
            ->where('source_type', '!=', 'open_trip')
            ->get()
            ->map(function ($schedule) {
                $start = $schedule->start_date ? \Carbon\Carbon::parse($schedule->start_date) : null;
                $end = $schedule->end_date ? \Carbon\Carbon::parse($schedule->end_date) : null;

                $booking = Booking::where('schedule_id', $schedule->id)->first();

                $los = 0;
                if ($start && $end) {
                    $los = $start->diffInDays($end) + 1;
                } elseif ($start) {
                    $los = 1;
                }

                $guestType = $booking->guest_type ?? 'lokal';

                return (object) [
                    'visitor_name' => $booking->name ?? $schedule->visitor_name ?? '-',
                    'institution' => $booking->institution ?? '-',
                    'number_phone' => $booking->number_phone ?? '-',
                    'guest_type' => $guestType,
                    'guest_type_label' => $guestType === 'asing' ? 'Asing' : 'Lokal',
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date,
                    'length_of_stay' => $los,
                    'total_people' => $schedule->booked ?? 1,
                    'visit_type' => 'booking',
                    'visit_type_label' => 'Booking',
                    'status' => $schedule->type,
                    'status_label' => ucfirst($schedule->type),
                ];
            });

        $openTripVisits = OpenTripRegistration::query()
            ->whereYear('start_date', $year)
            ->whereIn('status', ['confirmed', 'completed'])
            ->get()
            ->map(function ($registration) {
                $start = $registration->start_date ? \Carbon\Carbon::parse($registration->start_date) : null;
                $end = $registration->end_date ? \Carbon\Carbon::parse($registration->end_date) : null;

                $los = 0;
                if ($start && $end) {
                    $los = $start->diffInDays($end) + 1;
                } elseif ($start) {
                    $los = 1;
                }

                $guestType = $registration->guest_type ?? 'lokal';

                return (object) [
                    'visitor_name' => $registration->name ?? '-',
                    'institution' => $registration->institution ?? '-',
                    'number_phone' => $registration->number_phone ?? '-',
                    'guest_type' => $guestType,
                    'guest_type_label' => $guestType === 'asing' ? 'Asing' : 'Lokal',
                    'start_date' => $registration->start_date,
                    'end_date' => $registration->end_date,
                    'length_of_stay' => $los,
                    'total_people' => $registration->people_count ?? 1,
                    'visit_type' => 'open_trip',
                    'visit_type_label' => 'Open Trip',
                    'status' => $registration->status,
                    'status_label' => ucfirst($registration->status),
                ];
            });

        $visits = $scheduleVisits->concat($openTripVisits);

        if ($request->filled('institution')) {
            $search = $request->institution;
            $visits = $visits->filter(function ($v) use ($search) {
                return stripos($v->institution, $search) !== false
                    || stripos($v->visitor_name, $search) !== false;
            })->values();
        }

        if ($request->filled('visit_type')) {
            $visits = $visits->where('visit_type', $request->visit_type)->values();
        }

        if ($request->filled('guest_type')) {
            $visits = $visits->where('guest_type', $request->guest_type)->values();
        }

        $visits = $visits->sortByDesc('start_date')->values();

        return $visits;
    }

    private function getSummary($visits)
    {
        $totalVisits = $visits->count();
        $totalPeople = $visits->sum('total_people');
        $totalLosDays = $visits->sum('length_of_stay');

        $institutions = $visits->pluck('institution')->unique()->filter(function ($v) {
            return $v && $v !== '-';
        })->count();

        $byType = $visits->groupBy('visit_type')->map(function ($group, $key) {
            return (object) [
                'type' => $key,
                'label' => $key === 'booking' ? 'Booking' : 'Open Trip',
                'count' => $group->count(),
                'people' => $group->sum('total_people'),
            ];
        })->values();

        $byGuestType = $visits->groupBy('guest_type')->map(function ($group, $key) {
            return (object) [
                'type' => $key,
                'label' => $key === 'asing' ? 'Asing' : 'Lokal',
                'count' => $group->count(),
                'people' => $group->sum('total_people'),
            ];
        })->values();

        return (object) [
            'total_visits' => $totalVisits,
            'total_people' => $totalPeople,
            'total_los_days' => $totalLosDays,
            'avg_los' => $totalVisits > 0 ? round($totalLosDays / $totalVisits, 1) : 0,
            'avg_people_per_visit' => $totalVisits > 0 ? round($totalPeople / $totalVisits, 1) : 0,
            'total_institutions' => $institutions,
            'by_type' => $byType,
            'by_guest_type' => $byGuestType,
        ];
    }

    private function getAvailableYears(): array
    {
        $scheduleYears = Schedule::where('is_active', true)
            ->whereNotNull('start_date')
            ->select(DB::raw('YEAR(start_date) as year'))
            ->distinct()
            ->pluck('year');

        $openTripYears = OpenTripRegistration::whereNotNull('start_date')
            ->select(DB::raw('YEAR(start_date) as year'))
            ->distinct()
            ->pluck('year');

        $allYears = $scheduleYears->merge($openTripYears)->unique()->sort()->values()->toArray();

        return $allYears;
    }
}