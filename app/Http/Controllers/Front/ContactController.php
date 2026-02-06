<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|email',
            'phone'     => 'required|min:8',
            'type'      => 'required',
            'message'   => 'required|min:5',
        ]);

        ContactMessage::create($request->all());

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda.');
    }
}
