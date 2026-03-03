<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

public function update(Request $request)
{
    $data = $request->except('_token');
    
    // Specify keys that handle file uploads
    $imageKeys = ['site_logo', 'site_favicon', 'site_banner', 'og_image'];
    
    foreach ($imageKeys as $key) {
        if ($request->hasFile($key)) {
            // 1. Find the old setting to get the existing file path
            $oldSetting = Setting::where('key', $key)->first();
            
            if ($oldSetting && $oldSetting->value) {
                // 2. Delete the old file from the 'public' disk if it exists
                if (Storage::disk('public')->exists($oldSetting->value)) {
                    Storage::disk('public')->delete($oldSetting->value);
                }
            }

            // 3. Store the new file
            $file = $request->file($key);
            $path = $file->store('settings', 'public');
            $data[$key] = $path;
        }
    }
    
    // 4. Update or Create settings in the database
    foreach ($data as $key => $value) {
        // Ensure we don't save null values if a file wasn't uploaded for a specific key
        if ($value !== null) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    return redirect()->back()->with('success', 'System settings updated successfully.');
}
}
