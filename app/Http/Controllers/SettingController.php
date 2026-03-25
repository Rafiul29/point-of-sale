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
    $maxUploadSizeKb = 10240; // 10MB per file

    $validated = $request->validate([
        'shop_name' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'shop_phone' => 'nullable|string|max:50',
        'shop_address' => 'nullable|string|max:1000',
        'currency_symbol' => 'nullable|string|max:10',
        'tax_percentage' => 'nullable|numeric|min:0|max:100',

        'site_logo' => "nullable|file|image|mimes:jpg,jpeg,png,svg,webp|max:{$maxUploadSizeKb}",
        'site_favicon' => "nullable|file|image|mimes:jpg,jpeg,png,svg,webp|max:{$maxUploadSizeKb}",
        'site_banner' => "nullable|file|image|mimes:jpg,jpeg,png,svg,webp|max:{$maxUploadSizeKb}",
        'og_image' => "nullable|file|image|mimes:jpg,jpeg,png,svg,webp|max:{$maxUploadSizeKb}",
    ], [
        'site_logo.max' => 'Logo must be 10MB or smaller.',
        'site_favicon.max' => 'Favicon must be 10MB or smaller.',
        'site_banner.max' => 'Banner must be 10MB or smaller.',
        'og_image.max' => 'OpenGraph image must be 10MB or smaller.',
    ]);

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
