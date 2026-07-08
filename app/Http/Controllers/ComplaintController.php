<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        Complaint::create([
            'message' => $request->message
        ]);

        Notification::create([
            'title' => 'Aduan Baru',
            'message' => $request->message
        ]);

        return back()->with('success', 'Aduan berhasil dikirim');
    }
}
