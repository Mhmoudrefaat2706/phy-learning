<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Settings\UpdateSettingRequest;

class SettingController extends Controller
{

    public function index()
    {
        $settings = Setting::all();
        return view('admin.pages.settings.list', compact('settings'));
    }

    public function show(Setting $setting)
    {
        return response()->json($setting);
    }

    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('admin/settings', 'public');
        }

        $setting->update($data);

        return redirect()->route('admin.settings.index')->with('message', 'Setting updated successfully!');
    }
}
