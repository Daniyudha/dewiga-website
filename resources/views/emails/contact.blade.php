@component('mail::message')
# New Contact Form Submission Website

Anda mendapat pesan baru dari from yang ada pada website:

- **Name:** {{ $data['name'] }}
- **Email:** {{ $data['email'] }}
- **Subject:** {{ $data['subject'] }}
- **Message:** 
{{ $data['message'] }}

Segera tindaklanjuti pesan ini untuk memberikan pelayanan yang baik. Terima kasih.

@endcomponent
