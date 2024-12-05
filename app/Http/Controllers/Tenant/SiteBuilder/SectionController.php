<?php

namespace App\Http\Controllers\Tenant\SiteBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SiteBuilder\SectionRequest;
use App\Models\Tenant\SiteBuilder\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(SectionRequest $request)
    {
        $page_id = $request->input('page_id');
        $index = $request->input('index');
        $type = $request->input('type');
        $properties = $request->input('properties');

        if ($type == 'advantages') {
            if (isset($properties['advantages'])) {
                foreach ($properties['advantages'] as &$advantage) {
                    if (isset($advantage['image'])) {
                        $section = new Section();
                        $section
                            ->addMedia($advantage['image'])
                            ->toMediaCollection('images');

                        $advantage['image'] = $section->getFirstMediaUrl('images');
                    }
                }
            }

            $section = Section::create([
                'page_id' => $page_id,
                'index' => $index,
                'type' => $type,
                'properties' => $properties,
            ]);

            return response()->json(
                $section
                , 201);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        //
    }
}
