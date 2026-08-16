<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $estimation->proposal_title ?? 'Proposal' }} - Desa Wisata Gabugan</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f4f3; color: #1a2e28; line-height: 1.6; padding: 20px; }
        .container { max-width: 850px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #032419, #0a4a35); color: #fff; padding: 28px 32px; text-align: center; }
        .header h1 { font-size: 20px; font-weight: 700; letter-spacing: 0.5px; }
        .header .title { font-size: 16px; color: #f5c518; margin-top: 6px; font-weight: 700; }
        .header .sub { font-size: 12px; opacity: 0.75; margin-top: 4px; }
        .header .dates { font-size: 12px; margin-top: 8px; opacity: 0.9; }
        .est-num { display: inline-block; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.25); padding: 4px 14px; border-radius: 20px; font-size: 12px; margin-top: 10px; }
        .toolbar { display: flex; justify-content: center; gap: 10px; padding: 12px; background: #eef3f1; flex-wrap: wrap; position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .btn { display: inline-block; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px; border: none; cursor: pointer; }
        .btn-download { background: #0a4a35; color: #fff; }
        .btn-wa { background: #25D366; color: #fff; }
        .btn-outline { background: #fff; color: #0a4a35; border: 1px solid #cbd5d1; }
        .body { padding: 24px 32px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 24px; }
        .info-item { background: #f6faf8; padding: 10px 14px; border-radius: 8px; border-left: 4px solid #00a878; }
        .info-item .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #68757f; font-weight: 600; }
        .info-item .value { font-size: 14px; font-weight: 600; margin-top: 2px; }
        .section-title { font-size: 17px; font-weight: 700; color: #032419; margin: 22px 0 10px; padding-bottom: 6px; border-bottom: 3px solid #16a34a; }
        .section-title-sm { font-size: 14px; font-weight: 700; color: #0a4a35; margin: 14px 0 6px; }
        .desc-text { font-size: 13px; text-align: justify; margin-bottom: 10px; }
        .info-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px; border-bottom: 1px dashed #e5eaee; }
        .info-row .k { color: #68757f; }
        .info-row .v { font-weight: 600; }
        .table-wrap { overflow-x: auto; margin: 10px 0 18px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        thead th { background: #0a4a35; color: #fff; text-align: left; padding: 9px 10px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; }
        tbody td { padding: 8px 10px; border-bottom: 1px solid #eceff1; }
        .num { text-align: right; font-variant-numeric: tabular-nums; }
        .day-card { background: #f6faf8; border-radius: 8px; padding: 14px 16px; margin-bottom: 14px; border-left: 4px solid #00a878; }
        .day-card h4 { font-size: 13px; color: #032419; margin-bottom: 8px; }
        .summary { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px 20px; margin-top: 16px; }
        .summary-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; }
        .summary-row.total { border-top: 2px solid #86efac; margin-top: 8px; padding-top: 12px; font-weight: 800; font-size: 17px; color: #007a5e; }
        .terms { font-size: 12px; color: #333; }
        .terms h4 { color: #0a4a35; margin: 12px 0 4px; font-size: 13px; }
        .closing { background: #f6faf8; border-radius: 10px; padding: 18px; margin-top: 20px; border-left: 4px solid #16a34a; }
        .footer { text-align: center; padding: 14px 20px; background: #032419; color: #fff; font-size: 11px; }
        .contact-bar { display: flex; justify-content: space-between; align-items: center; gap: 12px; background: #0a4a35; color: #fff; padding: 12px 24px; font-size: 12px; flex-wrap: wrap; }
        @media (max-width: 600px) { .container { border-radius: 8px; } .body { padding: 16px; } .header { padding: 20px 16px; } .info-grid { grid-template-columns: 1fr; } .toolbar { position: static; } }
        @media print {
            body { background: #fff; padding: 0; }
            .container { box-shadow: none; border-radius: 0; max-width: 100%; }
            .toolbar, .btn-wa { display: none !important; }
            .header { background: #032419 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .section-title { border-bottom-color: #16a34a; }
            thead th { background: #0a4a35 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .summary { background: #f0fdf4 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .day-card, .closing { background: #f6faf8 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DESA WISATA GABUGAN</h1>
            <div class="title">{{ $estimation->proposal_title ?? 'Program Kunjungan Edukasi' }}</div>
            <div class="sub">{{ $estimation->program_subtitle ?? 'Proposal Program Kegiatan Wisata Edukasi & Kenal Desa' }}</div>
            <div class="dates">{{ $estimation->arrival_date->format('d F Y') }} s.d. {{ $estimation->departure_date->format('d F Y') }}</div>
            <div class="est-num">{{ $estimation->proposal_number ?? $estimation->estimation_number }}</div>
        </div>

        <div class="body">
            <div class="info-grid" style="grid-template-columns: 1fr 1fr;">
                <div class="info-item"><div class="label">Sekolah / Instansi</div><div class="value">{{ $estimation->institution_name }}</div></div>
                <div class="info-item"><div class="label">Penanggung Jawab</div><div class="value">{{ $estimation->contact_person }} / {{ $estimation->whatsapp }}</div></div>
                <div class="info-item"><div class="label">Tanggal Kunjungan</div><div class="value">{{ $estimation->arrival_date->format('d/m/Y') }} - {{ $estimation->departure_date->format('d/m/Y') }}</div></div>
                <div class="info-item"><div class="label">Jumlah Peserta</div><div class="value">{{ $estimation->student_count }} siswa + {{ $estimation->companion_count }} pendamping = {{ $estimation->service_participant_count }} orang</div></div>
            </div>

            {{-- PROFIL DESA --}}
            @if($settings)
            <div class="section-title">Profil Desa Wisata Gabugan</div>
            <h4 class="section-title-sm">Tentang Kami</h4>
            <p class="desc-text">{{ $settings->short_profile ?? 'Desa Wisata Gabugan merupakan destinasi wisata edukasi yang menawarkan pengalaman belajar berbasis budaya dan kearifan lokal.' }}</p>
            <h4 class="section-title-sm">Visi</h4>
            <p class="desc-text">{{ $settings->vision ?? '-' }}</p>
            <h4 class="section-title-sm">Misi</h4>
            <p class="desc-text">{!! nl2br(e($settings->mission ?? '-')) !!}</p>
            @if($settings->advantages)
            <h4 class="section-title-sm">Keunggulan</h4>
            <p class="desc-text">{{ $settings->advantages }}</p>
            @endif
            @if($settings->commitment)
            <h4 class="section-title-sm">Komitmen Kami</h4>
            <p class="desc-text">{{ $settings->commitment }}</p>
            @endif
            <div class="info-row"><span class="k">Lokasi</span><span class="v">{{ $settings->location ?? '-' }}</span></div>
            @if($settings->maps_url)<div class="info-row"><span class="k">Maps</span><a class="v" href="{{ $settings->maps_url }}" target="_blank">{{ $settings->maps_url }}</a></div>@endif
            <div class="info-row"><span class="k">Kontak</span><span class="v">{{ $settings->contact ?? '-' }}</span></div>
            @endif

            {{-- PROGRAM --}}
            <div class="section-title">Program</div>
            <h4 class="section-title-sm">{{ $estimation->proposal_title ?? 'Program Kunjungan Edukasi' }}</h4>
            @if($estimation->program_subtitle)<p class="desc-text" style="color:#333;">{{ $estimation->program_subtitle }}</p>@endif

            @if($estimation->program_objective)
            <h4 class="section-title-sm" style="margin-top:14px;">Tujuan Program</h4>
            <p class="desc-text">{{ $estimation->program_objective }}</p>
            @endif
            @if($estimation->learning_outputs)
            <h4 class="section-title-sm">Output Pembelajaran</h4>
            <p class="desc-text">{{ $estimation->learning_outputs }}</p>
            @endif
            @if($estimation->village_advantages)
            <h4 class="section-title-sm">Keunggulan Desa Wisata Gabugan</h4>
            <p class="desc-text">{{ $estimation->village_advantages }}</p>
            @endif

            {{-- RUNDOWN --}}
            @if($hasRundown)
            <div class="section-title">Rundown Kegiatan</div>
            @foreach($rundownItems as $day => $items)
            <div class="day-card">
                <h4><i class="fas fa-calendar-day"></i> Hari ke-{{ $day }} — {{ $estimation->arrival_date->copy()->addDays($day - 1)->translatedFormat('l, d F Y') }}</h4>
                <div class="table-wrap" style="margin-bottom:0;">
                    <table>
                        <thead><tr><th style="width:70px;">Waktu</th><th>Kegiatan</th><th>Lokasi</th><th>PIC</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            @foreach($items->sortBy('sort_order') as $item)
                            <tr>
                                <td class="num" style="white-space:nowrap;">@if($item->start_time){{ substr($item->start_time,0,5) }}@if($item->end_time)–{{ substr($item->end_time,0,5) }}@endif @else – @endif</td>
                                <td>{{ $item->activity_name }}</td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>{{ $item->person_in_charge ?? '-' }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            @endif

            {{-- PILIHAN KEGIATAN & FASILITAS --}}
            <div class="section-title">Pilihan Kegiatan & Fasilitas</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <h4 class="section-title-sm">Pilihan Kegiatan</h4>
                    <table>
                        <thead><tr><th style="width:30px;">No</th><th>Kegiatan</th></tr></thead>
                        <tbody>
                            @foreach($allActivities as $i => $activity)
                            <tr><td class="num">{{ $loop->iteration }}</td><td>{{ $activity->title_id }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <h4 class="section-title-sm">Fasilitas</h4>
                    <table>
                        <thead><tr><th style="width:30px;">No</th><th>Fasilitas</th></tr></thead>
                        <tbody>
                            @foreach($defaultFacilities as $i => $fac)
                            <tr><td class="num">{{ $i + 1 }}</td><td>{{ $fac }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PENAWARAN HARGA --}}
            <div class="section-title">Penawaran Harga</div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Komponen</th><th class="num">Qty</th><th class="num">Frekuensi</th><th class="num">Harga Satuan</th><th class="num">Harga/Orang</th><th class="num">Jumlah</th></tr></thead>
                    <tbody>
                        @foreach($estimation->items as $item)
                        <tr>
                            <td>{{ $item->item_code === 'guide_fund' ? 'Pemandu' : $item->item_name }}</td>
                            <td class="num">{{ $item->quantity }}</td>
                            <td class="num">{{ $item->frequency }} {{ $item->unit }}</td>
                            <td class="num">IDR {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="num">IDR {{ number_format($item->price_per_person, 0, ',', '.') }}</td>
                            <td class="num" style="font-weight:600;">IDR {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="summary">
                <div class="summary-row"><span>Grand Total Biaya</span><span>IDR {{ number_format($estimation->subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Harga per Orang (Sebelum Pembulatan)</span><span>IDR {{ number_format($estimation->actual_price_per_person, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Harga per Orang (Setelah Pembulatan)</span><span style="font-weight:700; color:#166534;">IDR {{ number_format($estimation->rounded_price_per_person, 0, ',', '.') }}</span></div>
                <div class="summary-row total"><span>TOTAL QUOTATION</span><span>IDR {{ number_format($estimation->quotation_total, 0, ',', '.') }}</span></div>
                @if($estimation->notes)
                <div class="summary-row" style="margin-top:8px; font-size:12px; font-style:italic; border-top:1px dashed #ccc;"><span>Catatan:</span><span>{{ $estimation->notes }}</span></div>
                @endif
            </div>

            {{-- KETENTUAN --}}
            @if($settings)
            <div class="section-title">Ketentuan Program</div>
            <div class="terms">
                @if($settings->dp_terms)<h4>Uang Muka (DP)</h4><p class="desc-text">{{ $settings->dp_terms }}</p>@endif
                @if($settings->payment_terms)<h4>Pelunasan Pembayaran</h4><p class="desc-text">{{ $settings->payment_terms }}</p>@endif
                @if($settings->cancellation_terms)<h4>Pembatalan</h4><p class="desc-text">{{ $settings->cancellation_terms }}</p>@endif
                @if($settings->participant_change_terms)<h4>Perubahan Peserta</h4><p class="desc-text">{{ $settings->participant_change_terms }}</p>@endif
                @if($settings->force_majeure_terms)<h4>Force Majeure</h4><p class="desc-text">{{ $settings->force_majeure_terms }}</p>@endif
                @if($settings->homestay_terms)<h4>Ketentuan Homestay</h4><p class="desc-text">{{ $settings->homestay_terms }}</p>@endif
            </div>
            @endif

            {{-- PENUTUP --}}
            <div class="closing">
                <h3 class="section-title-sm" style="font-size:16px; color:#0d2137;">Penutup</h3>
                <p class="desc-text">Demikian proposal program ini kami sampaikan. Besar harapan kami untuk dapat bekerja sama dalam mewujudkan program edukasi yang bermanfaat bagi peserta didik. Kami percaya bahwa program <strong>"{{ $estimation->proposal_title ?? 'Program Kunjungan Edukasi' }}"</strong> akan memberikan pengalaman belajar yang berharga dan tak terlupakan.</p>
                <p class="desc-text">Kami terbuka untuk melakukan diskusi lebih lanjut dan menyesuaikan program sesuai dengan kebutuhan dan tujuan pembelajaran instansi Bapak/Ibu. Setiap masukan dan saran akan kami terima dengan senang hati.</p>
                <p class="desc-text">Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
                @if($settings && $settings->tagline)
                <p style="margin-top:12px; font-style:italic; color:#166534; text-align:center; font-weight:600;">"{{ $settings->tagline }}"</p>
                @endif
            </div>

            <div style="text-align:center; margin-top:20px;">
                <a class="btn btn-download" href="{{ route('public.proposal.pdf', $estimation->estimation_number) }}"><i class="fas fa-download"></i> Download PDF</a>
                <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode('Assalamualaikum, saya ingin bertanya mengenai proposal ' . ($estimation->proposal_number ?? $estimation->estimation_number) . ' (' . $estimation->institution_name . ')') }}" class="btn btn-wa" target="_blank">
                    <i class="fab fa-whatsapp"></i> Tanya-Tanya via WhatsApp
                </a>
            </div>
        </div>

        <div class="contact-bar">
            <span>📍 {{ $settings->location ?? 'Dusun Gabugan, Donokerto, Turi, Sleman, Yogyakarta' }}</span>
            <span>📞 {{ $settings->contact ?? '+62 813 2885 6252' }}</span>
        </div>
        <div class="footer">© {{ date('Y') }} Desa Wisata Gabugan — Proposal dihasilkan secara otomatis.</div>
    </div>
</body>
</html>