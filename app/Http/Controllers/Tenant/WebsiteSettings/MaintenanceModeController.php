<?php

namespace App\Http\Controllers\Tenant\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebsiteSettings\MaintenanceModeRequest;
use App\Models\Tenant\WebsiteSettings\MaintenanceMode;
use Illuminate\Http\Request;

class MaintenanceModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MaintenanceMode::latest()->first();
        if (!$data) {
            return response()->json(['message' => 'لا توجد بيانات.'], 404);
        }
        return response()->json(['data' => $data], 200);
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
    public function store(MaintenanceModeRequest $request)
    {
        $data = MaintenanceMode::latest()->first();

        if ($data) {
            $data->update($request->validated());
        } else {
            $data = MaintenanceMode::create($request->validated());
        }

        return response()->json(['message' => 'تم حفظ الإعدادات بنجاح.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(MaintenanceMode $maintenanceMode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceMode $maintenanceMode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceMode $maintenanceMode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceMode $maintenanceMode)
    {
        //
    }
}
