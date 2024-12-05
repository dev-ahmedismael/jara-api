<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Message\MessageRequest;
use App\Http\Requests\Tenant\Shared\DestroyBulkRequest;
use App\Http\Requests\Tenant\Shared\FilterRequest;
use App\Http\Requests\Tenant\Shared\SearchRequest;
use App\Http\Requests\Tenant\Shared\SortRequest;
use App\Models\Tenant\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $messages = Message::latest()->paginate(10);
        return response()->json($messages, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request)
    {
        Message::create($request->all());
        return response()->json(['message' => 'تم الإرسال بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $message = Message::findOrFail($id);
        return response()->json(['message' => $message], 200);
    }

    //    Search
    public function search(SearchRequest $request)
    {
        $keyword = $request->input('keyword');

        $table = (new Message())->getTable();
        $columns = Schema::getColumnListing($table);

        $query = Message::query();
        $query->where(function ($q) use ($keyword, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$keyword}%");
            }
        });

        $messages = $query->paginate(10);

        return response()->json($messages, 200);
    }

    //    Sort
    public function sort(SortRequest $request)
    {
        $query = Message::query();

        $sortBy = $request->input('sort_by');
        $direction = $request->input('direction') ?? 'asc';

        $query->orderBy($sortBy, $direction);

        $messages = $query->paginate(10);

        return response()->json($messages, 200);
    }


    //    Filter
    public function filter(FilterRequest $request)
    {
        $query = Message::query();

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

        $messages = $query->paginate(10);

        return response()->json($messages, 200);
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy_bulk(DestroyBulkRequest $request)
    {
        $ids = $request->input('ids');

        Message::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'تم الحذف بنجاح.'], 200);
    }

    public function destroy(string $id)
    {
        Message::destroy($id);
        return response()->json(['message' => 'تم حذف الرسالة بنجاح.'], 200);
    }
}
