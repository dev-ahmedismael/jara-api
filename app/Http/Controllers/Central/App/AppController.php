<?php

namespace App\Http\Controllers\Central\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\App\AppRequest;
use App\Models\Central\App\App;

class AppController extends Controller
{
    public function index()
    {
        return response()->json(App::with('media')->get());
    }

    public function store(AppRequest $request)
    {
        $app = App::create($request->validated());

        if ($request->hasFile('image')) {
            $app->addMedia($request->file('image'))->toMediaCollection('apps');
        }

        return response()->json(['message' => 'تم إضافة التطبيق بنجاح.'], 201);
    }

    public function show(string $id)
    {
        $app = App::with('media')->findOrFail($id);
        return response()->json(['data' => $app]);
    }

    public function update(AppRequest $request, string $id)
    {
        $app = App::findOrFail($id);
        $app->update($request->validated());

        if ($request->hasFile('image')) {
            $app->clearMediaCollection('apps');
            $app->addMedia($request->file('image'))->toMediaCollection('apps');
        }

        return response()->json( ['message' => 'تم تحديث التطبيق بنجاح.'], 200);
    }

    public function destroy(string $id)
    {
        $app = App::findOrFail($id);
        $app->clearMediaCollection('apps');
        $app->delete();

        return response()->json(['message' => 'تم حذف التطبيق بنجاح.']);
    }
}
