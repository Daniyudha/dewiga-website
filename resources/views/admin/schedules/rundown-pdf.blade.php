<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rundown - {{ $schedule->visitor_name ?? 'Jadwal' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #2563eb; }
        .header h1 { font-size: 16pt; color: #2563eb; margin: 0 0 5px; }
        .header p { margin: 2px 0; font-size: 9pt; color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 8px; font-size: 9pt; }
        .info-table td:first-child { font-weight: bold; width: 120px; color: #555; }
        .day-section { margin-bottom: 20px; page-break-inside: avoid; }
        .day-title { background: #2563eb; color: white; padding: 6px 12px; font-size: 11pt; font-weight: bold; border-radius: 4px 4px 0 0; }
        .day-title .date { font-weight: normal; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th { background: #f0f4ff; padding: 6px 8px; text-align: left; font-size: 9pt; border: 1px solid #ddd; }
        td { padding: 5px 8px; border: 1px solid #ddd; font-size: 9pt; }
        .time-col { width: 70px; font-family: 'Courier', monospace; white-space: nowrap; }
        .notes { margin-top: 20px; padding: 10px; background: #f9fafb; border-radius: 4px; font-size: 9pt; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 8pt; color: #999; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUNDOWN KEGIATAN</h1>
        <p>{{ $rundown->title }}</p>
        @if($rundown->rundown_number)
            <p>No. {{ $rundown->rundown_number }}</p>
        @endif
    </div>

    <table class="info-table">
        <tr><td>Rombongan</td><td>: {{ $schedule->visitor_name ?? '-' }}</td></tr>
        <tr><td>Instansi</td><td>: {{ $schedule->priceEstimation->institution_name ?? '-' }}</td></tr>
        <tr><td>Tanggal Kunjungan</td><td>: {{ $schedule->start_date?->format('d/m/Y') }} {{ $schedule->end_date ? ' - ' . $schedule->end_date->format('d/m/Y') : '' }}</td></tr>
        <tr><td>Jumlah Peserta</td><td>: {{ $schedule->booked }} orang</td></tr>
        <tr><td>Status</td><td>: {{ $schedule->status_label }}</td></tr>
    </table>

    @php $groupedItems = $rundown->items->groupBy('day_number'); @endphp

    @foreach($groupedItems as $day => $items)
        @php $firstItem = $items->first(); @endphp
        <div class="day-section">
            <div class="day-title">
                HARI KE-{{ $day }}
                @if($firstItem && $firstItem->activity_date)
                    <span class="date">– {{ \Carbon\Carbon::parse($firstItem->activity_date)->translatedFormat('l, d F Y') }}</span>
                @endif
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="time-col">Waktu</th>
                        <th>Kegiatan</th>
                        <th>Lokasi</th>
                        <th>Penanggung Jawab</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items->sortBy('sort_order') as $item)
                    <tr>
                        <td class="time-col">
                            @if($item->start_time)
                                {{ substr($item->start_time, 0, 5) }}
                                @if($item->end_time) – {{ substr($item->end_time, 0, 5) }} @endif
                            @else - @endif
                        </td>
                        <td>{{ $item->activity_name }}</td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td>{{ $item->person_in_charge ?? '-' }}</td>
                        <td>{{ $item->description ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if($rundown->notes)
        <div class="notes">
            <strong>Catatan:</strong><br>{{ $rundown->notes }}
        </div>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} | {{ config('app.name') }}
    </div>
</body>
</html>