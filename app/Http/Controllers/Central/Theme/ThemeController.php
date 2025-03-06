<?php
namespace App\Http\Controllers\Central\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Theme\ThemeRequest;
use App\Models\Central\Theme\Theme;

class ThemeController extends Controller
{
public function index()
{
return response()->json(Theme::with('media')->get());
}

public function store(ThemeRequest $request)
{
$theme = Theme::create($request->validated());

if ($request->hasFile('image')) {
$theme->addMedia($request->file('image'))->toMediaCollection('themes');
}

return response()->json(['message' => 'تم إنشاء الثيم بنجاح.'], 201);
}

public function show(string $id)
{
$theme = Theme::findOrFail($id);

return response()->json(['data' => $theme], 200);
}

public function update(ThemeRequest $request, string $id)
{
$theme = Theme::findOrFail($id);
$theme->update($request->validated());

if ($request->hasFile('image')) {
$theme->addMedia($request->file('image'))->toMediaCollection('themes');
}

return response()->json(['message' => 'تم تحديث الثيم بنجاح.'], 200);
}

public function destroy(string $id)
{
Theme::destroy($id);
return response()->json(['message' => 'تم حذف الثيم بنجاح.'], 200);
}
}
