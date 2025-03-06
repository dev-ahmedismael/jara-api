<?php

namespace App\Http\Controllers\Tenant\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Theme\ThemeRequest;
use App\Models\Tenant\Theme\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
     public function install(ThemeRequest $request) {

         $theme = Theme::latest()->first();
         if(!$theme) {
             Theme::create(
                 [
                     'theme_id' => $request->input('theme_id'),
                     'title' => $request->input('title'),
                     'primary_color' => $request->input('custom_primary_color'),
                     'secondary_color' => $request->input('custom_secondary_color'),
                     'tertiary_color' => $request->input('custom_tertiary_color'),
                     'custom_primary_color' => $request->input('custom_primary_color'),
                     'custom_secondary_color' => $request->input('custom_secondary_color'),
                     'custom_tertiary_color' => $request->input('custom_tertiary_color'),
                 ]
             );
         }

         $theme->update([
             'theme_id' => $request->input('theme_id'),
             'title' => $request->input('title'),
             'primary_color' => $request->input('custom_primary_color'),
             'secondary_color' => $request->input('custom_secondary_color'),
             'tertiary_color' => $request->input('custom_tertiary_color'),
             'custom_primary_color' => $request->input('custom_primary_color'),
             'custom_secondary_color' => $request->input('custom_secondary_color'),
             'custom_tertiary_color' => $request->input('custom_tertiary_color'),
         ]);

         return response()->json(['message' => 'تم تثبيت الثيم بنجاح.'], 201);
     }

     public function installed_theme(Request $request)
     {
         $theme = Theme::latest()->first();
         if(!$theme) {
             return response()->json(['message' => 'الثيم غير موجود']);
         }
         return response()->json(['data' => $theme], 200);
     }
     public function customize(ThemeRequest $request) {
         $theme = Theme::latest()->first();
         if (!$theme) {
             return response()->json(['message' => 'الثيم غير موجود.'], 404);
         }
         $theme->update([
             'custom_primary_color' => $request->input('custom_primary_color'),
             'custom_secondary_color' => $request->input('custom_secondary_color'),
             'custom_tertiary_color' => $request->input('custom_tertiary_color'),
         ]);

         return response()->json(['message' => 'تم تخصيص الثيم بنجاح.'], 200);
     }
     public function reset(Request $request) {
         $theme = Theme::latest()->first();
         if (!$theme) {
             return response()->json(['message' => 'الثيم غير موجود.'], 404);
         }
         $theme->update([
             'custom_primary_color' => $theme->primary_color,
             'custom_secondary_color' => $theme->secondary_color,
             'custom_tertiary_color' => $theme->tertiary_color,
         ]);

         return response()->json(['message' => 'تم إعادة ضبط الثيم بنجاح.'], 200);
     }
}
