<?php

namespace App\Http\Controllers\Tenant\SiteBuilder;

use App\Http\Controllers\Controller;
use App\Models\Tenant\SiteBuilder\PolicyPage;
use Illuminate\Http\Request;

class PolicyPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = PolicyPage::all();
        return response()->json($pages, 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = PolicyPage::find($id);

        return response()->json($page, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PolicyPage $policyPage, string $id)
    {
        $page = PolicyPage::findOrFail($id);
        $page->update($request->all());
        return response()->json($page, 200);
    }
}
