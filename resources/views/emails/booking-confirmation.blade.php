@php
$lang = app()->getLocale();
@endphp

@component('mail::message')
@if($lang === 'id')
# Konfirmasi Pemesanan

Terima kasih telah memesan! Kami telah menerima permintaan Anda dan akan memprosesnya segera.

**Detail Pemesanan:**
- **Nama:** {{ $booking->name }}
- **Instansi:** {{ $booking->institution ?? '-' }}
- **Email:** {{ $booking->email }}
- **Telepon:** {{ $booking->number_phone }}
- **Tanggal Kunjungan:** {{ $booking->date->format('d M Y') }}@if($booking->end_date) hingga {{ $booking->end_date->format('d M Y') }}@endif
- **Jumlah Orang:** {{ $booking->people_count ?? 1 }}
- **Paket:** {{ $booking->travelPackage->type ?? 'N/A' }}

Tim kami akan menghubungi Anda dalam 24 jam untuk mengkonfirmasi pemesanan Anda.

Terima kasih telah memilih Dewiga Travel!

Salam hangat,<br>
Tim Dewiga Travel
@else
# Booking Confirmation

Thank you for your booking! We have received your request and will process it shortly.

**Booking Details:**
- **Name:** {{ $booking->name }}
- **Institution:** {{ $booking->institution ?? '-' }}
- **Email:** {{ $booking->email }}
- **Phone:** {{ $booking->number_phone }}
- **Visit Date:** {{ $booking->date->format('d M Y') }}@if($booking->end_date) to {{ $booking->end_date->format('d M Y') }}@endif
- **Number of People:** {{ $booking->people_count ?? 1 }}
- **Package:** {{ $booking->travelPackage->type ?? 'N/A' }}

Our team will contact you within 24 hours to confirm your booking.

Thank you for choosing Dewiga Travel!

Best regards,<br>
Dewiga Travel Team
@endif
@endcomponent
