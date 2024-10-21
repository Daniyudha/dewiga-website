<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        try {
            // Mengirim email
            Mail::to(config('mail.from.address'))->send(new ContactMail($data));

            return response()->json(['message' => 'Pesan anda berhasil terkirim!']);
        } catch (\Exception $e) {
            // Penanganan kesalahan saat mengirim email
            return response()->json(['message' => 'Failed to send email. Please try again later.'], 500);
        }
    }
}
