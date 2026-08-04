<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function index()
    {
        $defaultSettings = [
            'app_name' => 'Smart Field Audit System',
            'contact_email' => 'admin@fieldaudit.com',
            'map_default_view' => 'hybrid',
            'map_auto_refresh' => '30',
            'notify_system_alerts' => '1',
            'notify_email_summaries' => '0',
        ];

        $dbSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $settings = array_merge($defaultSettings, $dbSettings);
        
        // Convert string booleans back to actual booleans for the view
        $settings['notify_system_alerts'] = (bool) $settings['notify_system_alerts'];
        $settings['notify_email_summaries'] = (bool) $settings['notify_email_summaries'];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        // Checkboxes only send data when checked. We explicitly handle them.
        $checkboxes = ['notify_system_alerts', 'notify_email_summaries'];
        foreach ($checkboxes as $checkbox) {
            $data[$checkbox] = $request->has($checkbox) ? '1' : '0';
        }

        foreach ($data as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return Redirect::route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
