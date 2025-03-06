<?php

namespace App\Http\Controllers\Tenant\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebsiteSettings\SocialMediaUrlRequest;
use App\Models\Tenant\WebsiteSettings\SocialMediaUrl;
use Illuminate\Http\Request;

class SocialMediaUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SocialMediaUrl::latest()->first();
        if (!$data) {
            return response()->json(['message' => 'لا يوجد بيانات.']);
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
    public function store(SocialMediaUrlRequest $request)
    {
        $data = SocialMediaUrl::latest()->first();

        if ($data) {
            $data->update($request->validated());
        } else {
            $data = SocialMediaUrl::create($request->validated());
        }

        return response()->json([
            'data' => $data,
            'message' => 'تم حفظ البيانات بنجاح.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMediaUrl $socialMediaUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMediaUrl $socialMediaUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMediaUrl $socialMediaUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMediaUrl $socialMediaUrl)
    {
        //
    }
}
