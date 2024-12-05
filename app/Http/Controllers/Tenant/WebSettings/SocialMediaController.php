<?php

namespace App\Http\Controllers\Tenant\WebSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebSettings\SocialMediaRequest;
use App\Models\Tenant\WebSettings\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $social_media = SocialMedia::latest()->first();
        return response()->json(['data' => $social_media], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialMediaRequest $request)
    {
        $social_media = SocialMedia::latest()->first();
        if ($social_media) {
            $social_media->update($request->all());

            return response()->json([
                'message' => 'تم تحديث الروابط بنجاح.',
                'data' => $social_media,
            ], 200);
        } else {
            $social_media = SocialMedia::create($request->all());

            return response()->json([
                'message' => 'تم حفظ الروابط بنجاح.',
                'data' => $social_media,
            ], 201);
        }
    }


}
