<?php

namespace App\Http\Controllers\Tenant\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\WebsiteSettings\PageRequest;
use App\Models\Tenant\WebsiteSettings\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::latest()->get(['id', 'name', 'path']);

        return response()->json([
            'data' => $pages
        ], 200);
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
    public function store(PageRequest $request)
    {
        $page = Page::create($request->only(['name', 'path', 'title', 'description']));

         if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $page->addMedia($image)->toMediaCollection('pages');
            }
        }

         if ($request->has('videos')) {
            $page->videos = json_encode($request->videos);
        }

        $page->save();

        return response()->json(['message' => 'تم حفظ البيانات بنجاح.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $path)
    {
        $page = Page::with('media')->where('path', $path)->firstOrFail();

        return response()->json([
            'data' => [
                'name' => $page->name,
                'path' => $page->path,
                'title' => $page->title,
                'description' => $page->description,
                'images' => $page->images_urls,
                'videos' => $page->videos,
            ]
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, string $path)
    {
        $page = Page::where('path', $path)->first();

        if (!$page) {
            return response()->json(['message' => 'الصفحة غير موجودة.'], 404);
        }

        $page->update($request->only(['title', 'description']));

        if ($request->hasFile('images')) {
            $page->clearMediaCollection('pages');

            foreach ($request->file('images') as $image) {
                $page->addMedia($image)->toMediaCollection('pages');
            }
        }

        $page->videos = $request->input('videos');

        $page->save();

        $page->load('media');

        return response()->json(['message' => 'تم تحديث البيانات بنجاح.', 'data' => $page], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        //
    }
}
