<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        \App\Models\Feedback::create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return redirect()->back()->with('success', 'Feedback sent successfully!');
    }
}
