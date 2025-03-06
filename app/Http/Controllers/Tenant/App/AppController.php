<?php

namespace App\Http\Controllers\Tenant\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\App\AppRequest;
use App\Models\Central\App\App as CentralApp;
use App\Models\Tenant\App\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    public function index()
    {
        $installed_apps = App::with('media')->latest()->get();

        return response()->json([
              'installed_apps' => $installed_apps
         ], 200);
    }

    public function store(AppRequest $request)
    {
        $app = $request->validated();

        $tenant = tenant(); // Get the current tenant

        $tenant->run(function () use ($app) {
            App::create([
                'app_id' => $app['id'],
                'title' => $app['title'],
                'description' => $app['description'],
                'type' => $app['type'],
                'auth_url' => $app['auth_url'],
                'fields' => $app['fields'],
                'price' => $app['price'],
                'image' => $app['image'],
            ]);
        });

        return response()->json(['message' => 'تم تثبيت التطبيق بنجاح.'], 201);
    }

    public function show(string $id)
    {
        $app = App::with('media')->findOrFail($id);
        return response()->json(['data' => $app]);
    }

    public function update(AppRequest $request, string $id)
    {
        $app = App::findOrFail($id);
        $app->update($request->only(['fields', 'access_token', 'refresh_token']));

        return response()->json( ['message' => 'تم تحديث البيانات بنجاح.'], 200);
    }

    public function destroy(string $id)
    {
        $app = App::findOrFail($id);
        $app->clearMediaCollection('apps');
        $app->delete();

        return response()->json(['message' => 'تم إلغاء تثبيت التطبيق بنجاح.']);
    }

    public function store_zoom_tokens (Request $request)
    {
        $validated = $request->validate(['code' => 'string|required']);

        $client_id = 'TH3LsYb4RryMYGKoSBJBQ';
        $client_secret = 'Ae0u8eD3tprxkxIEYqS4oMju43QzOmhP';
        $redirect_uri = 'http://localhost:4200/dashboard/jara-appstore/installed-apps/zoom-redirect';

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode("$client_id:$client_secret"),
        ])->post('https://zoom.us/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $validated['code'],
            'redirect_uri' => $redirect_uri,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'لم نتمكن من الحصول على البيانات اللازمة، يرجى حذف التطبيق ومحاولة تثبيته مرة أخرى.'], 400);
        }

        $tokenData = $response->json();

        $app = App::where('title', 'تطبيق Zoom')->firstOrFail();
        $expires_in = now()->addSeconds($tokenData['expires_in']);
        $app->update([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'],
            'expires_in' =>  $expires_in
        ]);
        $app->save();
        return response()->json(['message' => 'تم ربط حسابك على تطبيق Zoom مع منصة جرة بنجاح.' ], 200);
    }
}
