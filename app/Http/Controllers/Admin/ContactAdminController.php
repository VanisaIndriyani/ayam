<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;

class ContactAdminController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contacts.index', compact('messages'));
    }

    public function show(ContactMessage $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }
}
