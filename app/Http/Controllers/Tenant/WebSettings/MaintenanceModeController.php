<?php

namespace App\Http\Controllers\Tenant\WebSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\MaintenanceMode\MaintenanceModeRequest;
use App\Models\Tenant\MaintenanceMode;

class MaintenanceModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MaintenanceMode::latest()->first();
        return response()->json(['data' => $data], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MaintenanceModeRequest $request)
    {
        $validatedData = $request->validated();

        $maintenanceMode = MaintenanceMode::updateOrCreate(
            [],
            $validatedData
        );

        return response()->json([
            'message' => $maintenanceMode->wasRecentlyCreated
                ? 'تم تفعيل وضع الصيانة بنجاح.'
                : 'تم تحديث وضع الصيانة بنجاح.',
            'data' => $maintenanceMode,
        ], 200);
    }


}
