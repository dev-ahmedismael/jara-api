<?php

namespace App\Http\Controllers\Central\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Setting\SettingRequest;
use App\Models\Central\Setting\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::latest()->first();
        return response()->json(['data' => $settings], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        $setting = Setting::latest()->first();

        if(!$setting){
            Setting::create($request->validated());

            return response()->json(['message' => 'تم ضبط الإعدادات بنجاح.'], 201);
        }

        $setting->update($request->validated());

        return response()->json(['message' => 'تم تحديث الإعدادات بنجاح.'], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
