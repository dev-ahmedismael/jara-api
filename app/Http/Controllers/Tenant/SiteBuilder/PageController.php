<?php

namespace App\Http\Controllers\Tenant\SiteBuilder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Shared\DestroyBulkRequest;
use App\Http\Requests\Tenant\Shared\FilterRequest;
use App\Http\Requests\Tenant\Shared\SearchRequest;
use App\Http\Requests\Tenant\Shared\SortRequest;
use App\Http\Requests\Tenant\SiteBuilder\PageRequest;
use App\Models\Tenant\Message;
use App\Models\Tenant\SiteBuilder\Page;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return response()->json($pages, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        $isExsits = Page::where('slug', $request->input('slug'))->exists();
        if ($isExsits) {
            return response()->json(['message' => 'توجد صفحة بموقعك تستخدم نفس الرابط.'], 422);
        }
        Page::create($request->all());
        $pages = Page::latest()->paginate(10);
        return response()->json($pages, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = Page::findOrFail($id);
        return response()->json($page, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PageRequest $request, string $id)
    {
        $page = Page::findOrFail($id);
        $page->fill($request->all());
        $page->save();
        return response()->json($page, 200);
    }

    //    Search
    public function search(SearchRequest $request)
    {
        $keyword = $request->input('keyword');

        $table = (new Page())->getTable();
        $columns = Schema::getColumnListing($table);

        $query = Page::query();
        $query->where(function ($q) use ($keyword, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$keyword}%");
            }
        });

        $pages = $query->paginate(10);

        return response()->json($pages, 200);
    }

    //    Sort
    public function sort(SortRequest $request)
    {
        $query = Page::query();

        $sortBy = $request->input('sort_by');
        $direction = $request->input('direction') ?? 'asc';

        $query->orderBy($sortBy, $direction);

        $pages = $query->paginate(10);

        return response()->json($pages, 200);
    }


    //    Filter
    public function filter(FilterRequest $request)
    {
        $query = Page::query();

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

        $pages = $query->paginate(10);

        return response()->json($pages, 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy_bulk(DestroyBulkRequest $request)
    {
        $ids = $request->input('ids');

        Page::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'تم الحذف بنجاح.'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Page::destroy($id);
        return response()->json(['message' => 'تم حذف الصفحة بنجاح.'], 204);
    }
}
