<?php

namespace App\Http\Controllers\Tenant\WebSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebSettings\BasicInfoRequest;
use App\Models\Tenant\WebSettings\BasicInfo;
use Illuminate\Http\Request;

class BasicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $basic_info = BasicInfo::latest()->first();
        if ($basic_info) {
            $basic_info->load('media');
        }
        return response()->json(['data' => $basic_info], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BasicInfoRequest $request)
    {
        $basicInfo = BasicInfo::latest()->first();

        if ($basicInfo) {
            $basicInfo->update($request->all());
        } else {
            $basicInfo = BasicInfo::create($request->all());
        }

        if ($request->hasFile('logo')) {
            $basicInfo->addMedia($request->file('logo'))->toMediaCollection('logo');
        }

        if ($request->hasFile('icon')) {
            $basicInfo->addMedia($request->file('icon'))->toMediaCollection('icon');
        }

        return response()->json([
            'message' => $basicInfo->wasRecentlyCreated ? 'تم حفظ البيانات بنجاح.' : 'تم حفظ التغييرات بنجاح.',
            'data' => $basicInfo->load('media'),
        ], $basicInfo->wasRecentlyCreated ? 201 : 200);
    }
}
