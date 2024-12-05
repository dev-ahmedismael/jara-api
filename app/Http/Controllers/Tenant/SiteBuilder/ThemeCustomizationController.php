<?php

namespace App\Http\Controllers\Tenant\SiteBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SiteBuilder\ThemeCustomizationRequest;
use App\Models\Tenant\SiteBuilder\ThemeCustomization;
use Illuminate\Http\Request;

class ThemeCustomizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $theme = ThemeCustomization::latest()->first();
        return response()->json($theme, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ThemeCustomizationRequest $request)
    {
        $themeCustomization = ThemeCustomization::updateOrCreate(
            ['id' => 1],
            $request->validated()
        );

        return response()->json([
            'message' => 'تم حفظ الإعدادات بنجاح.',
            'data' => $themeCustomization,
        ], $request->filled('id') ? 200 : 201);
    }


}
