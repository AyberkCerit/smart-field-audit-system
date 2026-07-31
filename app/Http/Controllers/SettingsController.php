<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function index()
    {
        // Settings could be loaded from a config, database, or just hardcoded for mockup purposes.
        $settings = [
            'app_name' => 'Smart Field Audit System',
            'contact_email' => 'admin@fieldaudit.com',
            'map_default_view' => 'hybrid',
            'map_auto_refresh' => '30',
            'notify_system_alerts' => true,
            'notify_email_summaries' => false,
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Mock update logic. In a real scenario, validate and save to DB or cache.
        // We will just redirect back with a success message.
        return Redirect::route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
