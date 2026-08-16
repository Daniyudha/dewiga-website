<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estimasi {{ $estimation->estimation_number }} - Desa Wisata Gabugan</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f3;
            color: #1a2e28;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #032419, #0a4a35);
            color: #fff;
            padding: 32px 36px;
            text-align: center;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header .sub {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 4px;
        }
        .est-num {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 12px;
            letter-spacing: 0.5px;
        }
        .body { padding: 28px 36px; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 26px;
        }
        .info-item {
            background: #f6faf8;
            padding: 12px 16px;
            border-radius: 10px;
            border-left: 4px solid #00a878;
        }
        .info-item .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5b7d71;
            font-weight: 600;
        }
        .info-item .value {
            font-size: 15px;
            font-weight: 600;
            color: #1a2e28;
            margin-top: 2px;
        }
        h2 {
            font-size: 16px;
            color: #032419;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e6eee9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-wrap { overflow-x: auto; margin-bottom: 24px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        thead th {
            background: #f2f7f4;
            text-align: left;
            padding: 10px 12px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5b7d71;
            border-bottom: 2px solid #dce9e2;
        }
        tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #eef4f1;
            color: #1a2e28;
        }
        .num { font-variant-numeric: tabular-nums; text-align: right; }
        .summary {
            background: #f6faf8;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
        }
        .summary-row.total {
            border-top: 2px solid #dce9e2;
            margin-top: 8px;
            padding-top: 12px;
            font-weight: 700;
            font-size: 17px;
            color: #007a5e;
        }
        .contact-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: #032419;
            color: #fff;
            padding: 18px 36px;
            font-size: 13px;
            flex-wrap: wrap;
        }
        .contact-bar a {
            color: #00c887;
            text-decoration: none;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            padding: 18px;
            background: #eef4f1;
            color: #7a8a83;
            font-size: 11px;
        }
        .btn-whatsapp {
            display: inline-block;
            background: #25D366;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 4px;
        }
        .btn-download {
            display: inline-block;
            background: #0a4a35;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 4px;
        }
        .toolbar {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            background: #eef3f1;
            flex-wrap: wrap;
        }
        @media (max-width: 600px) {
            .container { border-radius: 8px; }
            .body { padding: 18px 16px; }
            .header { padding: 24px 16px; }
            .info-grid { grid-template-columns: 1fr; }
            .contact-bar { padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DESA WISATA GABUGAN</h1>
            <div class="sub">Sleman • Yogyakarta</div>
            <div class="est-num">{{ $estimation->estimation_number }}</div>
        </div>

        <div class="body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Sekolah / Instansi</div>
                    <div class="value">{{ $estimation->institution_name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Penanggung Jawab</div>
                    <div class="value">{{ $estimation->contact_person }} / {{ $estimation->whatsapp }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Tanggal Kunjungan</div>
                    <div class="value">{{ $estimation->arrival_date->format('d/m/Y') }} - {{ $estimation->departure_date->format('d/m/Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Jumlah Peserta</div>
                    <div class="value">{{ $estimation->service_participant_count }} orang</div>
                </div>
            </div>

            @php
                $mainItems = $estimation->items->sortBy('sort_order')->reject(fn($i) => str_starts_with($i->item_code, 'custom_addon') || $i->item_code === 'other_addon');
                $addonItems = $estimation->items->filter(fn($i) => str_starts_with($i->item_code, 'custom_addon') || $i->item_code === 'other_addon');
            @endphp
            <h2>Rincian Biaya</h2>
            <div class="table-wrap">
                @if($mainItems->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th class="num">Qty</th>
                            <th class="num">Frekuensi</th>
                            <th class="num">Harga Satuan</th>
                            <th class="num">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mainItems as $item)
                        <tr>
                            <td>{{ $item->item_code === 'guide_fund' ? 'Pemandu' : $item->item_name }}</td>
                            <td class="num">{{ $item->quantity }}</td>
                            <td class="num">{{ $item->frequency }} {{ $item->unit }}</td>
                            <td class="num">{{ formatPrice($item->unit_price) }}</td>
                            <td class="num">{{ formatPrice($item->total) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p style="color:#8a9a93; text-align:center; padding:20px;">Belum ada komponen biaya.</p>
                @endif
            </div>

            @if($addonItems->count() > 0)
            <h2>Item Tambahan</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th class="num">Qty</th>
                            <th class="num">Frekuensi</th>
                            <th class="num">Satuan</th>
                            <th class="num">Harga Satuan</th>
                            <th class="num">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addonItems as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td class="num">{{ $item->quantity }}</td>
                            <td class="num">{{ $item->frequency }}</td>
                            <td class="num">{{ $item->unit }}</td>
                            <td class="num">{{ formatPrice($item->unit_price) }}</td>
                            <td class="num">{{ formatPrice($item->total) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="summary">
                <div class="summary-row">
                    <span>Grand Total Biaya</span>
                    <span>{{ formatPrice($estimation->subtotal) }}</span>
                </div>
                <div class="summary-row">
                    <span>Harga Aktual per Orang</span>
                    <span>{{ formatPrice($estimation->actual_price_per_person) }}</span>
                </div>
                <div class="summary-row">
                    <span>Harga per Orang (Setelah Pembulatan)</span>
                    <span>{{ formatPrice($estimation->rounded_price_per_person) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Quotation</span>
                    <span>{{ formatPrice($estimation->quotation_total) }}</span>
                </div>
                @if($estimation->notes)
                <div class="summary-row" style="margin-top:12px; font-size:12px; font-style:italic;">
                    <span>Catatan:</span>
                    <span>{{ $estimation->notes }}</span>
                </div>
                @endif
            </div>

            <div style="text-align:center; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                <a href="{{ route('public.estimation.pdf', $estimation->estimation_number) }}" class="btn-download" target="_blank">
                    ⬇ Download PDF
                </a>
                <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode('Assalamualaikum, saya ingin bertanya mengenai estimasi ' . $estimation->estimation_number . ' (' . $estimation->institution_name . ')') }}" class="btn-whatsapp" target="_blank">
                    Tanya-Tanya via WhatsApp
                </a>
            </div>
        </div>

        <div class="contact-bar">
            <span>📍 Dusun Gabugan, Kalurahan Donokerto, Turi, Sleman, Yogyakarta</span>
            <span>📞 +62 813 2885 6252</span>
        </div>
        <div class="footer">
            © {{ date('Y') }} Desa Wisata Gabugan — Estimasi dihasilkan secara otomatis.
        </div>
    </div>
</body>
</html>