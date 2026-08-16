<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proposal - {{ $proposal->institution_name }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 10pt; 
            color: #333; 
            line-height: 1.6; 
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 60px 50px;
            page-break-after: always;
            position: relative;
        }
        .page:last-child { page-break-after: avoid; }
        
        .bg-img {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1;
        }
        .bg-img img { width: 100%; height: 100%; }

        .page-number {
            position: absolute;
            bottom: 65px;
            right: 70px;
            font-size: 11pt;
            color: #176b22;
        }

        .cover {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .cover .logo { width: 120px; margin-bottom: 30px; }
        .cover h1 { font-size: 24pt; color: #166534; margin: 0 0 10px; }
        .cover h2 { font-size: 14pt; color: #16a34a; font-weight: normal; margin: 0 0 30px; }
        .cover .divider { width: 100px; height: 3px; background: #16a34a; margin: 0 auto 30px; }
        .cover .institution { font-size: 13pt; color: #555; }
        .cover .date { font-size: 11pt; color: #888; margin-top: 10px; }

        .section-title { font-size: 14pt; color: #166534; border-bottom: 2px solid #16a34a; padding-bottom: 5px; margin-bottom: 10px; }
        .section-title-sm { font-size: 11pt; color: #166534; margin-bottom: 6px; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 12px; }
        .info-table td { padding: 3px 6px; font-size: 9pt; }
        .info-table td:first-child { font-weight: bold; width: 140px; color: #555; }

        .day-title { background: #166534; color: white; padding: 5px 10px; font-size: 10pt; font-weight: bold; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 9pt; }
        th { background: #166534; color: white; padding: 3px 4px; text-align: left; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.2px; border: none; }
        td { padding: 2.5px 4px; border-bottom: 0.5px solid #ddd; border-left: none; border-right: none; border-top: none; vertical-align: middle; font-size: 8pt; }
        tr:nth-child(even) { background: rgba(22, 101, 52, 0.03); }
        .text-right { text-align: right; }
        .text-mono { font-family: 'Courier', monospace; }
        .time-col { width: 55px; font-family: 'Courier', monospace; white-space: nowrap; }
        
        .compact-table th { background: #166534; color: white; padding: 2px 4px; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.2px; border: none; }
        .compact-table td { padding: 2px 4px; border-bottom: 0.5px solid #ddd; border-left: none; border-right: none; border-top: none; font-size: 7.5pt; }
        .compact-table tr:nth-child(even) { background: rgba(22, 101, 52, 0.03); }

        .price-total { font-weight: bold; color: #166534; }

        .close { padding-top: 60px; }
        .close h3 { font-size: 14pt; color: #166534; margin-bottom: 15px; text-align: center; }
        .close .desc { text-align: justify; margin-bottom: 30px; }
        .close .signature-block { text-align: right; margin-top: 20px; }
        .close .signature-block .signoff { width: 180px; margin: 0 0 5px auto; }
        .close .signature-block .name { font-weight: bold; margin-top: 5px; }
        .close .contact { font-size: 10pt; color: #555; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    @php $pageNum = 0; @endphp

    {{-- PAGE 1: COVER --}}
    @php $pageNum++; @endphp
    <div class="page cover">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-judul.png') }}" alt="bg"></div>
        <h1 style="margin-top: 300px;">PROPOSAL PROGRAM</h1>
        <h2>Desa Wisata Gabugan</h2>
        <div class="divider"></div>
        <div class="institution">{{ $proposal->proposal_title ?? 'Program Kunjungan Edukasi' }}</div>
        <div class="institution" style="margin-top:10px; font-weight:bold;">{{ $proposal->institution_name }}</div>
        <div class="date">{{ $proposal->arrival_date->format('d F Y') }} s.d. {{ $proposal->departure_date->format('d F Y') }}</div>
        @if($proposal->proposal_number)
        <div class="date" style="margin-top:20px; font-size:9pt;">No. {{ $proposal->proposal_number }}</div>
        @endif
    </div>

    {{-- PAGE 2: PROFIL DESA --}}
    @php $pageNum++; @endphp
    <div class="page">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>
        <div class="section-title">Profil Desa Wisata Gabugan</div>
        <h4 style="margin-bottom:5px;">Tentang Kami</h4>
        <p style="text-align:justify;">{{ $settings->short_profile ?? 'Desa Wisata Gabugan merupakan destinasi wisata edukasi yang menawarkan pengalaman belajar berbasis budaya dan kearifan lokal. Terletak di kawasan pedesaan yang asri, desa wisata ini menjadi tempat ideal bagi siswa untuk belajar di luar kelas melalui konsep Rural Culture Experience.' }}</p>

        <h4 style="margin-top:12px; margin-bottom:5px;">Visi</h4>
        <p style="text-align:justify;">{{ $settings->vision ?? 'Menjadi desa wisata edukasi terdepan yang melestarikan budaya lokal dan memberikan pengalaman belajar bermakna bagi generasi muda.' }}</p>

        <h4 style="margin-top:12px; margin-bottom:5px;">Misi</h4>
        <p style="text-align:justify;">{{ $settings->mission ?? '1. Menyediakan program edukasi berbasis budaya dan pertanian yang interaktif. 2. Memberdayakan masyarakat lokal sebagai pelaku utama pariwisata. 3. Mengembangkan potensi desa sebagai laboratorium belajar hidup. 4. Menjaga kelestarian lingkungan dan nilai-nilai kearifan lokal.' }}</p>

        @if($settings->advantages)
        <h4 style="margin-top:12px; margin-bottom:5px;">Keunggulan</h4>
        <p style="text-align:justify;">{{ $settings->advantages }}</p>
        @endif

        @if($settings->commitment)
        <h4 style="margin-top:12px; margin-bottom:5px;">Komitmen Kami</h4>
        <p style="text-align:justify;">{{ $settings->commitment }}</p>
        @endif

        <table class="info-table" style="margin-top:10px;">
            @if($settings->location)<tr><td>Lokasi</td><td>{{ $settings->location }}</td></tr>@endif
            @if($settings->maps_url)<tr><td>Maps</td><td style="color: royalblue;">{{ $settings->maps_url }}</td></tr>@endif
            @if($settings->contact)<tr><td>Kontak</td><td>{{ $settings->contact }}</td></tr>@endif
        </table>
    </div>

    {{-- PAGE 3: PROGRAM --}}
    @php $pageNum++; @endphp
    <div class="page">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>
        <div class="section-title">Program</div>
        <h4>{{ $proposal->proposal_title ?? 'Program Kunjungan Edukasi' }}</h4>
        @if($proposal->program_subtitle)<p style="color:#666;">{{ $proposal->program_subtitle }}</p>@endif
        <table class="info-table" style="margin-top:15px;">
            <tr><td>Nama Instansi</td><td>{{ $proposal->institution_name }}</td></tr>
            <tr><td>Penanggung Jawab</td><td>{{ $proposal->contact_person }}</td></tr>
            <tr><td>WhatsApp</td><td>{{ $proposal->whatsapp }}</td></tr>
            <tr><td>Tanggal Kunjungan</td><td>{{ $proposal->arrival_date->format('d/m/Y') }} – {{ $proposal->departure_date->format('d/m/Y') }}</td></tr>
            <tr><td>Jumlah Siswa</td><td>{{ $proposal->student_count }} orang</td></tr>
            <tr><td>Jumlah Pendamping</td><td>{{ $proposal->companion_count }} orang</td></tr>
        </table>
        @if($proposal->program_objective)<h4 style="margin-top:15px;">Tujuan Program</h4><p style="text-align:justify;">{{ $proposal->program_objective }}</p>@endif
        @if($proposal->learning_outputs)<h4 style="margin-top:15px;">Output Pembelajaran</h4><p style="text-align:justify;">{{ $proposal->learning_outputs }}</p>@endif
        @if($proposal->village_advantages)<h4 style="margin-top:15px;">Keunggulan Desa Wisata Gabugan</h4><p style="text-align:justify;">{{ $proposal->village_advantages }}</p>@endif
    </div>

    {{-- PAGES: RUNDOWN PER HARI --}}
    @php
        $hasRundown = false;
        if ($proposal->rundown_template_id) {
            $template = \App\Models\RundownTemplate::with('items')->find($proposal->rundown_template_id);
            if ($template) {
                $hasRundown = true;
                $rundownItems = $template->items->groupBy('day_number');
            }
        }
    @endphp
    @if($hasRundown)
        @foreach($rundownItems as $day => $items)
        @php $pageNum++; @endphp
        <div class="page">
            <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
            <div class="page-number">{{ $pageNum }}</div>
            <div class="section-title">Rundown Kegiatan – Hari ke-{{ $day }}</div>
            <div class="day-title">{{ $proposal->arrival_date->copy()->addDays($day - 1)->translatedFormat('l, d F Y') }}</div>
            <table>
                <thead><tr><th class="time-col">Waktu</th><th>Kegiatan</th><th>Lokasi</th><th>Penanggung Jawab</th><th>Keterangan</th></tr></thead>
                <tbody>
                    @foreach($items->sortBy('sort_order') as $item)
                    <tr>
                        <td class="time-col">@if($item->start_time){{ substr($item->start_time,0,5) }}@if($item->end_time)–{{ substr($item->end_time,0,5) }}@endif @else – @endif</td>
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
    @endif

    {{-- PAGE: KEGIATAN + FASILITAS (satu halaman) --}}
    @php
        $allActivities = \App\Models\Activity::orderBy('order')->get();
    @endphp
    @php $pageNum++; @endphp
    <div class="page">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>

        <div class="section-title">Pilihan Kegiatan</div>
        <table class="compact-table">
            <thead><tr><th style="width:30px;">No</th><th>Kegiatan</th></tr></thead>
            <tbody>
                @foreach($allActivities as $i => $activity)
                <tr><td style="text-align:center;">{{ $loop->iteration }}</td><td>{{ $activity->title_id }}</td></tr>
                @endforeach
            </tbody>
        </table>

        @php
            $defaultFacilities = ['Homestay', 'Makan', 'Snack', 'Pemandu', 'Pilih 5 Aktivitas', 'Transportasi', 'Dokumentasi', 'Dukungan Audio Dasar', 'Welcome Drink', 'Parkir', 'Pendopo'];
        @endphp
        <div class="section-title-sm" style="margin-top:15px;">Fasilitas</div>
        <table class="compact-table">
            <thead><tr><th style="width:30px;">No</th><th>Fasilitas</th></tr></thead>
            <tbody>
                @foreach($defaultFacilities as $i => $fac)
                <tr><td style="text-align:center;">{{ $i + 1 }}</td><td>{{ $fac }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGE: PENAWARAN HARGA --}}
    @php $pageNum++; @endphp
    <div class="page">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>
        <div class="section-title">Penawaran Harga</div>
        <table>
            <thead>
                <tr>
                    <th>Komponen</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Frekuensi</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Harga/Orang</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->items as $item)
                <tr>
                    <td>
                        {{ $item->item_code === 'guide_fund' ? 'Pemandu' : $item->item_name }}
                        @if($item->has_multiplier)
                        <br><span style="font-size:7px; color:#6b7280;">× {{ $item->multiplier }} (pengali)</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-center">{{ $item->frequency }} {{ $item->unit }}</td>
                    <td class="text-right text-mono">IDR {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right text-mono">
                        @if(in_array($item->item_code, ['cultural_performance', 'live_music', 'professional_sound', 'stage_lighting', 'sound_lighting_package', 'custom_addon_1', 'custom_addon_2', 'custom_addon_3', 'custom_addon_4', 'custom_addon_5', 'other_addon', 'pickup', 'cooking_competition']))
                            -
                        @else
                            IDR {{ number_format($item->price_per_person, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right text-mono" style="font-weight:bold;">IDR {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight:bold; background:#f0fdf4;"><td colspan="5">Grand Total Biaya</td><td style="text-align:right;">IDR {{ number_format($proposal->subtotal, 0, ',', '.') }}</td></tr>
                <tr style="background:#f9fafb;"><td colspan="5">Harga per Orang (Sebelum Pembulatan)</td><td style="text-align:right;">IDR {{ number_format($proposal->actual_price_per_person, 0, ',', '.') }}</td></tr>
                <tr style="font-weight:bold; color:#166534; background:#dcfce7;"><td colspan="5">Harga per Orang (Setelah Pembulatan)</td><td style="text-align:right; font-size:12pt;">IDR {{ number_format($proposal->rounded_price_per_person, 0, ',', '.') }}</td></tr>
                <tr style="font-weight:bold; color:#166534; font-size:13pt;"><td colspan="5">TOTAL QUOTATION</td><td style="text-align:right;">IDR {{ number_format($proposal->quotation_total, 0, ',', '.') }}</td></tr>
            </tfoot>
        </table>
    </div>

    {{-- PAGE: KETENTUAN --}}
    @php $pageNum++; @endphp
    <div class="page">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>
        <div class="section-title">Ketentuan Program</div>

        @if($settings->dp_terms)
        <h4 style="margin-top:8px;">Uang Muka (DP)</h4>
        <p style="text-align:justify;">{{ $settings->dp_terms }}</p>
        @endif

        @if($settings->payment_terms)
        <h4 style="margin-top:10px;">Pelunasan Pembayaran</h4>
        <p style="text-align:justify;">{{ $settings->payment_terms }}</p>
        @endif

        @if($settings->cancellation_terms)
        <h4 style="margin-top:10px;">Pembatalan</h4>
        <p style="text-align:justify;">{{ $settings->cancellation_terms }}</p>
        @endif

        @if($settings->participant_change_terms)
        <h4 style="margin-top:10px;">Perubahan Peserta</h4>
        <p style="text-align:justify;">{{ $settings->participant_change_terms }}</p>
        @endif

        @if($settings->force_majeure_terms)
        <h4 style="margin-top:10px;">Force Majeure</h4>
        <p style="text-align:justify;">{{ $settings->force_majeure_terms }}</p>
        @endif

        @if($settings->homestay_terms)
        <h4 style="margin-top:10px;">Ketentuan Homestay</h4>
        <p style="text-align:justify;">{{ $settings->homestay_terms }}</p>
        @endif
    </div>

    {{-- PAGE: PENUTUP --}}
    @php $pageNum++; @endphp
    <div class="page close">
        <div class="bg-img"><img src="{{ public_path('frontend/assets/img/cover-isi.png') }}" alt="bg"></div>
        <div class="page-number">{{ $pageNum }}</div>
        <h3>Penutup</h3>

        <div class="desc">
            <p>Demikian proposal program ini kami sampaikan. Besar harapan kami untuk dapat bekerja sama dalam mewujudkan program edukasi yang bermanfaat bagi peserta didik. Kami percaya bahwa program <strong>"{{ $proposal->proposal_title ?? 'Program Kunjungan Edukasi' }}"</strong> akan memberikan pengalaman belajar yang berharga dan tak terlupakan.</p>
            <p style="margin-top:10px;">Kami terbuka untuk melakukan diskusi lebih lanjut dan menyesuaikan program sesuai dengan kebutuhan dan tujuan pembelajaran instansi Bapak/Ibu. Setiap masukan dan saran akan kami terima dengan senang hati.</p>
            <p style="margin-top:15px;">Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
            @if($settings->tagline)
            <p style="margin-top:15px; font-style:italic; color:#166534; text-align:center;">"{{ $settings->tagline }}"</p>
            @endif
        </div>

        <div class="signature-block">
            @if(file_exists(public_path('frontend/assets/img/signoff.png')))
            <div class="signoff"><img src="{{ public_path('frontend/assets/img/signoff.png') }}" alt="Tanda Tangan" style="width:180px;"></div>
            @endif
        </div>
    </div>

    {{-- COVER BELAKANG --}}
    <div style="position:relative; width:100%; height:100%; margin:0; padding:0; page-break-after: avoid;">
        <img src="{{ public_path('frontend/assets/img/cover-belakang.png') }}" alt="bg" style="width:100%; height:100%; display:block;">
    </div>
</body>
</html>