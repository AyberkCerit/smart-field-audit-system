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

        $feedback = \App\Models\Feedback::create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'feedback' => $feedback->load('user'),
                'time' => $feedback->created_at->format('H:i')
            ]);
        }

        return redirect()->back()->with('success', 'Feedback sent successfully!');
    }
}
