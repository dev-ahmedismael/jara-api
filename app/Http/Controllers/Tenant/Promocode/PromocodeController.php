<?php

namespace App\Http\Controllers\Tenant\Promocode;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Promocode\PromocodeRequest;
use App\Models\Tenant\Promocode\Promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $promocodes = Promocode::filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $promocodes], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromocodeRequest $request)
    {
        Promocode::create($request->validated());
        return response()->json(['message' => 'تم إنشاء كوبون الخصم بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promocode = Promocode::findOrFail($id);
        return response()->json(['data' => $promocode]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PromocodeRequest $request, string $id)
    {
        $promocode = Promocode::find($id);
        $promocode->update($request->validated());
        return response()->json(['data' => $promocode, 'message' => 'تم التعديل بنجاح.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promocode $promocode, string $id)
    {
        $promocode->destroy($id);
        return response()->json(['message' => 'تم الحذف بنجاح.', 'f' => $promocode], 200);
    }
}
