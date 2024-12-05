<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\User\PurchasedAppRequest;
use App\Models\Tenant\PurchasedApp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PurchasedAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apps = PurchasedApp::all();
        return response()->json($apps);
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(PurchasedApp $purchasedApp, string $id)
    {
        $app = PurchasedApp::find($id);
        return response()->json($app);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PurchasedAppRequest $request, string $id)
    {
        $app = PurchasedApp::find($id);
        if (!$app) {
            $new_app = PurchasedApp::create($request->validated());
            return response()->json($new_app, 201);
        }
        $app->update($request->validated());
        return response()->json($app);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchasedApp $purchasedApp, string $id)
    {
        PurchasedApp::destroy($id);
        $apps = PurchasedApp::all();
        return response()->json($apps);
    }

    //    ZOOM
    //    Store Access Token
    public function store_zoom_access_token(Request $request)
    {
        
        $code = $request->input('code');
        $client = new Client();
        $response = $client->post('https://zoom.us/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('pcThtFKKQUOQbY1aS0KFZQ:2h95RqGhUUTCmrqeZ43p2R91rv05N335'),
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // Save tokens
        $app = PurchasedApp::where('slug', 'zoom')->first();
        if ($app) {
            $app->data = json_encode([
                'access_token' => $data['access_token'],
                'zoom_refresh_token' => $data['refresh_token'],
                'zoom_token_expires_at' => now()->addSeconds($data['expires_in']),
            ]);
            $app->save();
        }
        return response()->json($data);
    }

    //    Refresh Access Token
    public function refresh_zoom_access_token()
    {
        $app = PurchasedApp::where('slug', 'zoom')->first();
        if ($app && isset($app->data['zoom_token_expires_at'])) {
            $zoom_token_expires_at = $app->data['zoom_token_expires_at'];
            $zoom_refresh_token = $app->data['zoom_refresh_token'];
            if ($zoom_token_expires_at->isPast()) {

                $client = new Client();
                $response = $client->post('https://zoom.us/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $zoom_refresh_token,
                    ],
                    'headers' => [
                        'Authorization' => 'Basic ' . base64_encode('pcThtFKKQUOQbY1aS0KFZQ:2h95RqGhUUTCmrqeZ43p2R91rv05N335'),
                    ],
                ]);

                $data = json_decode($response->getBody(), true);

                // Save tokens
                $app->data = json_encode([
                    'access_token' => $data['access_token'],
                    'zoom_refresh_token' => $data['refresh_token'],
                    'zoom_token_expires_at' => now()->addSeconds($data['expires_in']),
                ]);
                $app->save();
            }
        }
    }


    //    Create New Meeting
    public function create_new_zoom_meeting(Request $request)
    {
        $meeting_details = $request->all();
        $this->refresh_zoom_access_token();

        $app = PurchasedApp::where('slug', 'zoom')->first();
        $zoom_access_token = $app->data['access_token'];
        $client = new Client();
        $response = $client->post('https://api.zoom.us/v2/users/me/meetings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $zoom_access_token,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'topic' => $meeting_details['topic'],
                'type' => 2, // Scheduled meeting
                'start_time' => $meeting_details['start_time'],
                'duration' => $meeting_details['duration'],
                'timezone' => $meeting_details['timezone'],
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
