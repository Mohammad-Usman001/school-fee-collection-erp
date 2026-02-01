<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        // ✅ if settings row not exist, create
        if (!$setting) {
            $setting = Setting::create([
                'school_name' => 'My School',
                'currency_symbol' => '₹',
                'receipt_prefix' => 'REC-',
                'backup_retention' => 30,
            ]);
        }

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create();
        }

        $request->validate([
            'school_name' => ['required','string','max:255'],
            'school_phone' => ['nullable','string','max:20'],
            'school_email' => ['nullable','email','max:255'],
            'school_address' => ['nullable','string','max:500'],

            'currency_symbol' => ['required','string','max:10'],
            'session_year' => ['nullable','string','max:20'],
            'receipt_prefix' => ['required','string','max:50'],
            'receipt_footer' => ['nullable','string','max:500'],
            'backup_retention' => ['required','integer','min:1','max:365'],

            'school_logo' => ['nullable','image','mimes:png,jpg,jpeg','max:2048'],
        ]);

        // ✅ handle logo upload
        if ($request->hasFile('school_logo')) {

            // delete old
            if ($setting->school_logo && File::exists(public_path($setting->school_logo))) {
                File::delete(public_path($setting->school_logo));
            }

            $file = $request->file('school_logo');
            $filename = 'school_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);

            $setting->school_logo = 'uploads/settings/' . $filename;
        }

        $setting->update([
            'school_name' => $request->school_name,
            'school_phone' => $request->school_phone,
            'school_email' => $request->school_email,
            'school_address' => $request->school_address,

            'currency_symbol' => $request->currency_symbol,
            'session_year' => $request->session_year,
            'receipt_prefix' => $request->receipt_prefix,
            'receipt_footer' => $request->receipt_footer,

            'backup_retention' => $request->backup_retention,
        ]);

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
