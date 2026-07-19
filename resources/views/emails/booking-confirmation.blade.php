<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Booking Confirmation') }} - Desa Wisata Gabugan</title>
    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        .email-header {
            background: linear-gradient(135deg, #166534 0%, #00a877 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .email-logo {
            max-width: 120px;
            margin-bottom: 16px;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .email-content {
            padding: 32px 24px;
        }
        .email-content h2 {
            color: #166534;
            font-size: 20px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 500;
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            color: #1e293b;
            text-align: right;
        }
        .package-badge {
            display: inline-block;
            background: #166534;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 14px;
            font-weight: 500;
        }
        .email-footer {
            background: #f1f5f9;
            padding: 24px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }
        .email-footer .copyright {
            margin-top: 16px;
            font-weight: 500;
            color: #334155;
        }
        .contact-info {
            margin-top: 16px;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="email-header">
            <img src="{{ asset('frontend/assets/img/brand-logo-outline.png') }}" alt="Desa Wisata Gabugan" class="email-logo">
            <h1>{{ __('Booking Confirmation') }}</h1>
        </div>

        <!-- Content -->
        <div class="email-content">
            @if(app()->getLocale() === 'id')
                <p>Terima kasih telah memesan! Kami telah menerima permintaan Anda dan akan memprosesnya segera.</p>
            @else
                <p>Thank you for your booking! We have received your request and will process it shortly.</p>
            @endif

            <h2>{{ __('Booking Details') }}</h2>

            <div class="detail-row">
                <span class="detail-label">{{ __('Name') }}</span>
                <span class="detail-value">{{ $booking->name }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Institution') }}</span>
                <span class="detail-value">{{ $booking->institution ?? '-' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Email') }}</span>
                <span class="detail-value">{{ $booking->email }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Phone') }}</span>
                <span class="detail-value">{{ $booking->number_phone }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Visit Date') }}</span>
                <span class="detail-value">
                    {{ $booking->date->format('d M Y') }}@if($booking->end_date) - {{ $booking->end_date->format('d M Y') }}@endif
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Number of People') }}</span>
                <span class="detail-value">{{ $booking->people_count ?? 1 }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">{{ __('Package') }}</span>
                <span class="detail-value">
                    @if($booking->travel_package)
                        <span class="package-badge">{{ $booking->travel_package->type }}</span>
                    @else
                        -
                    @endif
                </span>
            </div>

            @if($booking->amount)
            <div class="detail-row">
                <span class="detail-label">{{ __('Total Amount') }}</span>
                <span class="detail-value">Rp {{ number_format($booking->amount, 0, ',', '.') }}</span>
            </div>
            @endif

            @if(app()->getLocale() === 'id')
                <p style="margin-top: 24px;">Tim kami akan menghubungi Anda dalam 24 jam untuk mengkonfirmasi pemesanan Anda.</p>
            @else
                <p style="margin-top: 24px;">Our team will contact you within 24 hours to confirm your booking.</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>{{ __('Thank you for choosing Desa Wisata Gabugan!') }}</p>
            <p class="contact-info">
                Dusun Gabugan, Desa Donokerto, Kecamatan Turi, Kabupaten Sleman<br>
                Yogyakarta 55551 | +62 813 2885 6252
            </p>
            <div class="copyright">
                &copy; 2026 Desa Wisata Gabugan. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>