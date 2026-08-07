<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'message' => 'required|string|max:1000',
        ];

        if ($user->hasAnyRole(['admin', 'manager'])) {
            $rules['personnel_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        $personnelId = $user->hasAnyRole(['admin', 'manager']) ? $validated['personnel_id'] : $user->id;

        $feedback = \App\Models\Feedback::create([
            'user_id' => $user->id,
            'personnel_id' => $personnelId,
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

    public function history(Request $request, $personnelId)
    {
        // Ensure personnel can only view their own history
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'manager']) && $user->id != $personnelId) {
            abort(403, 'Unauthorized action.');
        }

        $feedbacks = \App\Models\Feedback::with('user')
            ->where('personnel_id', $personnelId)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        $formattedFeedbacks = $feedbacks->map(function($feedback) use ($user) {
            return [
                'id' => $feedback->id,
                'user_id' => $feedback->user_id,
                'name' => $feedback->user_id === $user->id ? 'You' : $feedback->user->name,
                'message' => $feedback->message,
                'time' => $feedback->created_at->format('H:i'),
                'is_own' => $feedback->user_id === $user->id,
            ];
        });

        return response()->json([
            'success' => true,
            'feedbacks' => $formattedFeedbacks
        ]);
    }
}
