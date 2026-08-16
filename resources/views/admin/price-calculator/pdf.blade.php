<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estimasi {{ $estimation->estimation_number }}</title>
    <style>
        @page {
            margin: 0px;
            size: A4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: transparent;
            color: #2d3748;
            font-size: 7.5px;
            line-height: 1.3;
        }

        .bg-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .bg-layer img {
            width: 100%;
            height: 100%;
            object-fit: fill;
        }

        .content {
            padding: 110px 60px 80px 60px;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            margin-top: 80px;
        }
        .header h1 {
            font-size: 14px;
            color: #166534;
            font-weight: 700;
            margin: 28px 0 2px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 9px;
            color: #166534;
            font-weight: 500;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header .est-no {
            font-size: 7.5px;
            color: #6b7280;
            margin-top: 3px;
        }

        .section-title {
            font-size: 8.5px;
            font-weight: 700;
            color: #166534;
            margin: 10px 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 0.8px solid #166534;
            padding-bottom: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .info-table td {
            padding: 1.5px 5px;
            vertical-align: top;
            font-size: 10px;
            border-bottom: 0.5px solid #ddd;
        }
        .info-table td:first-child {
            width: 28%;
            font-weight: 600;
            color: #166534;
        }
        .info-table td:last-child {
            width: 72%;
        }

        .component-list {
            margin: 8px 0;
            padding: 0;
        }
        .component-card {
            display: inline-block;
            background: rgba(11, 220, 91, 0.1);
            backdrop-filter: blur(8px);
            border: 0.5px solid rgba(22, 101, 52, 0.2);
            border-radius: 3px;
            padding: 5px 10px;
            margin: 3px 3px;
            font-size: 10px;
            color: #166534;
            font-weight: 500;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9px;
        }
        table.items th {
            background: #166534;
            color: white;
            padding: 3px 3px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        table.items td {
            padding: 2.5px 3px;
            border-bottom: 0.5px solid #ddd;
            vertical-align: middle;
        }
        table.items tr:nth-child(even) {
            background: rgba(22, 101, 52, 0.03);
        }
        table.items .text-right {
            text-align: right;
        }
        table.items .text-mono {
            font-family: 'DejaVu Sans Mono', monospace;
        }

        table.custom-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 9px;
        }
        table.custom-items th {
            background: #166534;
            color: white;
            padding: 3px 3px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        table.custom-items td {
            padding: 2.5px 3px;
            border-bottom: 0.5px solid #ddd;
        }
        table.custom-items tr:nth-child(even) {
            background: rgba(22, 101, 52, 0.03);
        }

        .summary {
            margin-top: 4px;
        }
        .summary table {
            width: 65%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .summary td {
            padding: 2px 6px;
            font-size: 9px;
        }
        .summary .label {
            text-align: right;
            color: #4a5568;
            width: 60%;
        }
        .summary .value {
            text-align: right;
            font-family: 'DejaVu Sans Mono', monospace;
            width: 40%;
        }
        .summary .total-label {
            font-weight: 700;
            color: #166534;
            font-size: 8px;
        }
        .summary .total-value {
            text-align: right;
            font-weight: 700;
            color: #166534;
            font-size: 9px;
        }
        .summary .border-top td {
            text-align: right;
            border-top: 1.2px solid #166534;
            padding-top: 3px;
        }

        .disclaimer {
            margin: 8px 10px 0 10px;
            padding: 5px 8px;
            border: 0.5px solid #ccc;
            border-radius: 2px;
            font-size: 6px;
            color: #6b7280;
            font-style: italic;
        }

        .signoff {
            margin-top: 10px;
            text-align: right;
            padding-right: 15px;
        }
        .signoff p {
            margin: 1px 0;
            font-size: 7px;
            color: #333;
        }
        .signoff .regards {
            margin-bottom: 8px;
        }
        .signoff .signoff-img {
            width: 150px;
            height: auto;
            margin: 3px 0;
        }
        .signoff .name {
            font-weight: 700;
            font-size: 7.5px;
            color: #166534;
        }
        .signoff .title {
            font-size: 6.5px;
            color: #555;
        }
        .signoff .village-name {
            font-size: 7.5px;
            font-weight: 600;
            color: #166534;
        }
    </style>
</head>
<body>
    @php
        $bgPath = public_path('frontend/assets/img/bg-doc.png');
        $bgExists = file_exists($bgPath);
    @endphp
    @if($bgExists)
    <div class="bg-layer">
        <img src="{{ $bgPath }}" alt="background">
    </div>
    @endif

    <div class="content">
        <div class="header">
            <h1>Estimasi Harga</h1>
            <h2>Rural Culture Experience</h2>
            <div class="est-no">{{ $estimation->estimation_number }}</div>
        </div>

        <div class="section-title">Informasi Rombongan</div>
        <table class="info-table">
            <tr><td>Nomor Estimasi</td><td>{{ $estimation->estimation_number }}</td></tr>
            <tr><td>Nama Instansi</td><td>{{ $estimation->institution_name }}</td></tr>
            <tr><td>Nama PIC</td><td>{{ $estimation->contact_person }}</td></tr>
            <tr><td>WhatsApp</td><td>{{ $estimation->whatsapp }}</td></tr>
            <tr><td>Tanggal Kedatangan</td><td>{{ $estimation->arrival_date->format('d/m/Y') }}</td></tr>
            <tr><td>Tanggal Kepulangan</td><td>{{ $estimation->departure_date->format('d/m/Y') }}</td></tr>
            <tr><td>Jumlah Peserta</td><td>{{ $estimation->student_count }} siswa + {{ $estimation->companion_count }} pendamping ({{ $estimation->service_participant_count }} layanan utama)</td></tr>
            <tr><td>Peserta Kegiatan</td><td>{{ $estimation->activity_participant_count }} orang</td></tr>
        </table>

        <div class="section-title">Komponen Program</div>
        @php
            $itemCodes = $estimation->items->pluck('item_code');
            $components = [
                'live_in' => 'Live In ' . ($estimation->items->firstWhere('item_code','live_in')?->frequency ?? '') . ' malam',
                'meal' => 'Makan ' . ($estimation->items->firstWhere('item_code','meal')?->frequency ?? '') . ' kali',
                'snack' => 'Snack ' . ($estimation->items->firstWhere('item_code','snack')?->frequency ?? '') . ' kali',
                'regular_activity' => ($estimation->items->firstWhere('item_code','regular_activity')?->frequency ?? '') . ' kegiatan wisata',
                'participant_art_activity' => 'Kesenian peserta',
                'cooking_competition' => 'Lomba Masak', 'pickup' => 'Pickup Wisata',
                'cultural_performance' => 'Pertunjukan Budaya',
                'professional_sound' => 'Sound Profesional', 'stage_lighting' => 'Lighting Panggung',
                'sound_lighting_package' => 'Paket Sound + Lighting', 'live_music' => 'Live Music',
            ];
            $hasCustom = $estimation->items->filter(fn($i) => str_starts_with($i->item_code, 'custom_addon'))->isNotEmpty();
        @endphp
        <div class="component-list">
            @foreach($components as $code => $label)
                @if($itemCodes->contains($code))
                    <span class="component-card">{{ $code === 'guide_fund' ? 'Pemandu' : $label }}</span>
                @endif
            @endforeach
            @if($hasCustom) <span class="component-card">Custom Add-on</span> @endif
            @if($estimation->items->firstWhere('item_code','other_addon')) <span class="component-card">Add-on Lainnya</span> @endif
        </div>

        <div class="section-title">Rincian Biaya</div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:26%">Komponen</th>
                    <th style="width:7%">Qty</th>
                    <th style="width:9%;">Frekuensi</th>
                    <th style="width:14%; text-align: right;">Harga Satuan</th>
                    <th style="width:14%; text-align: right;">Harga/Orang</th>
                    <th style="width:14%; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php $rowNum = 1; @endphp
                @foreach($estimation->items as $item)
                    @continue(str_starts_with($item->item_code, 'custom_addon') || $item->item_code === 'other_addon')
                    <tr>
                        <td>{{ $rowNum++ }}</td>
                        <td>{{ $item->item_code === 'guide_fund' ? 'Pemandu' : $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->frequency }} {{ $item->unit }}</td>
                        <td class="text-mono text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-mono text-right">
                            @if(in_array($item->item_code, ['cultural_performance', 'live_music', 'professional_sound', 'stage_lighting', 'sound_lighting_package', 'pickup', 'cooking_competition']))
                                -
                            @else
                                Rp {{ number_format($item->price_per_person, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-mono text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($hasCustom)
            <div class="section-title">Item Tambahan</div>
            <table class="custom-items">
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:24%">Nama Item</th>
                        <th style="width:16%">Keterangan</th>
                        <th style="width:6%">Qty</th>
                        <th style="width:8%">Freq</th>
                        <th style="width:9%">Satuan</th>
                        <th style="width:14%">Harga Satuan</th>
                        <th style="width:12%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $cNum = 1; @endphp
                    @foreach($estimation->items as $item)
                        @if(str_starts_with($item->item_code, 'custom_addon') || $item->item_code === 'other_addon')
                        <tr>
                            <td>{{ $cNum++ }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->calculation_details['description'] ?? ($item->item_code === 'other_addon' ? 'Add-on' : '-') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->frequency }}</td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-mono text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-mono text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="section-title">Ringkasan Estimasi</div>
        <div class="summary">
            <table>
                <tr><td class="label">Total Estimasi Biaya</td><td class="value">Rp {{ number_format($estimation->subtotal, 0, ',', '.') }}</td></tr>
                <tr><td class="label">Harga Aktual per Orang</td><td class="value">Rp {{ number_format($estimation->actual_price_per_person, 0, ',', '.') }}</td></tr>
                <tr><td class="label">Pembulatan Harga</td><td class="value">
                    @switch($estimation->rounding_type)
                        @case('up_1000') Ke atas Rp1.000 @break
                        @case('up_5000') Ke atas Rp5.000 @break
                        @case('up_10000') Ke atas Rp10.000 @break
                        @case('down_1000') Ke bawah Rp1.000 @break
                        @case('down_5000') Ke bawah Rp5.000 @break
                        @case('down_10000') Ke bawah Rp10.000 @break
                        @default Tanpa Pembulatan
                    @endswitch
                </td></tr>
                <tr><td class="label">Harga Estimasi per Orang</td><td class="value">Rp {{ number_format($estimation->rounded_price_per_person, 0, ',', '.') }}</td></tr>
                <tr class="border-top"><td class="total-label">TOTAL QUOTATION</td><td class="total-value">Rp {{ number_format($estimation->quotation_total, 0, ',', '.') }}</td></tr>
            </table>
        </div>

        <div class="disclaimer">
            Harga yang tercantum merupakan estimasi awal dan dapat berubah sesuai susunan kegiatan, jumlah peserta final, kebutuhan pendamping, serta kebutuhan tambahan lainnya.
        </div>

        @php
            $signoffPath = public_path('frontend/assets/img/signoff.png');
            $signoffExists = file_exists($signoffPath);
        @endphp
        <div class="signoff">
            @if($signoffExists) <img src="{{ $signoffPath }}" alt="TTD" class="signoff-img"> @endif
            <p style="font-size:6px; color:#666; margin-top:2px;">Rural Culture Experience</p>
        </div>
    </div>
</body>
</html>