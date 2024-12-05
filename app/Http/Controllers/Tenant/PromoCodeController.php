<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Promocode\PromoCodeRequest;
use App\Http\Requests\Tenant\Shared\DestroyBulkRequest;
use App\Http\Requests\Tenant\Shared\FilterRequest;
use App\Http\Requests\Tenant\Shared\SearchRequest;
use App\Http\Requests\Tenant\Shared\SortRequest;
use App\Models\Tenant\PromoCode;
use Illuminate\Support\Facades\Schema;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PromoCode::latest()->paginate(10);
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromoCodeRequest $request)
    {
        $promocode = PromoCode::create($request->all());
        return response()->json(['message' => 'تم إضافة الكود بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promocode = PromoCode::findOrFail($id);
        return response()->json(['data' => $promocode], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PromoCodeRequest $request, PromoCode $promoCode, string $id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->update($request->all());

        return response()->json(['message' => 'تم تعديل الكود بنجاح.'], 200);
    }


    //    Search
    public function search(SearchRequest $request)
    {
        $keyword = $request->input('keyword');

        $table = (new PromoCode())->getTable();
        $columns = Schema::getColumnListing($table);

        $query = PromoCode::query();
        $query->where(function ($q) use ($keyword, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$keyword}%");
            }
        });

        $promocodes = $query->paginate(10);

        return response()->json($promocodes, 200);
    }

    //    Sort
    public function sort(SortRequest $request)
    {
        $query = PromoCode::query();

        $sortBy = $request->input('sort_by');
        $direction = $request->input('direction') ?? 'asc';

        $query->orderBy($sortBy, $direction);

        $promocodes = $query->paginate(10);

        return response()->json($promocodes, 200);
    }


    //    Filter
    public function filter(FilterRequest $request)
    {
        $query = PromoCode::query();

        foreach ($request->all() as $key => $value) {
            if (is_array($value)) {
                // Handle numbers & dates filters
                if (isset($value['from'])) {
                    $query->where($key, '>=', $value['from']);
                }
                if (isset($value['to'])) {
                    $query->where($key, '<=', $value['to']);
                }
            } else {
                // Handle text filters
                $query->where($key, 'LIKE', '%' . $value . '%');
            }
        }

        $promocodes = $query->paginate(10);

        return response()->json($promocodes, 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy_bulk(DestroyBulkRequest $request)
    {
        $ids = $request->input('ids');

        PromoCode::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'تم الحذف بنجاح.'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PromoCode::destroy($id);
        return response()->json(['message' => 'تم حذف الكود بنجاح.'], 204);
    }
}
