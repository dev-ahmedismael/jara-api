<?php

namespace App\Http\Controllers\Tenant\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebsiteSettings\BasicInfoRequest;
use App\Models\Tenant\WebsiteSettings\BasicInfo;
use Illuminate\Http\Request;

class BasicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BasicInfo::latest()->first();
        $data->load('media');

        return response()->json(['data' => $data,'logo_url' => $data->getFirstMediaUrl('logo'),
            'icon_url' => $data->getFirstMediaUrl('icon')], 200);
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
    public function store(BasicInfoRequest $request)
    {
        $basicInfo = BasicInfo::firstOrNew();

        $basicInfo->fill($request->only(['name_ar', 'name_en', 'email', 'phone', 'description']));
        $basicInfo->save();

         if ($request->hasFile('logo')) {
            $basicInfo->clearMediaCollection('logo');
            $basicInfo->addMedia($request->file('logo'))->toMediaCollection('logo');
        }

         if ($request->hasFile('icon')) {
            $basicInfo->clearMediaCollection('icon');
            $basicInfo->addMedia($request->file('icon'))->toMediaCollection('icon');
        }

        return response()->json([
            'message' => 'تم حفظ البيانات بنجاح.',
            'data' => $basicInfo,
            'logo_url' => $basicInfo->getFirstMediaUrl('logo'),
            'icon_url' => $basicInfo->getFirstMediaUrl('icon'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BasicInfo $basicInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BasicInfo $basicInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BasicInfo $basicInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BasicInfo $basicInfo)
    {
        //
    }
}
