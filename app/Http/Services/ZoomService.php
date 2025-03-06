<?php

namespace App\Http\Services;

use App\Models\Tenant\App\App;
use Illuminate\Support\Facades\Http;


class ZoomService
{
    private $zoomBaseUrl = 'https://api.zoom.us/v2';

    public function scheduleMeeting($topic = "Consultation", $startTime = "2025-03-04T10:00:00Z", $duration = 30)
    {
        $accessToken = $this->getValidAccessToken();
        if (!$accessToken) {
            return ['error' => 'Zoom not connected or failed to refresh token'];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
        ])->post("$this->zoomBaseUrl/users/me/meetings", [
            'topic' => $topic,
            'type' => 2,
            'start_time' => $startTime,
            'duration' => $duration,
            'timezone' => 'UTC',
            'agenda' => '',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => false,
                'mute_upon_entry' => true,
                'waiting_room' => true,
                'approval_type' => 0,
            ],
        ]);

        if($response->failed()) {
            return ['error' => $response->json()];
        }
        return $response->successful() ? $response->json() : ['error' => 'Failed to create meeting'];
    }

    public function refreshAccessToken()
    {
        $tenant = tenant();
        tenancy()->initialize($tenant->id);

        $app = App::where('title', 'تطبيق Zoom')->first();

        if (!$app->refresh_token) {
            return null;
        }

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
        ])->post("https://zoom.us/oauth/token", [
            'grant_type' => 'refresh_token',
            'refresh_token' => $app->refresh_token,
        ]);


        if ($response->successful()) {
            $tokens = $response->json();
            $app->update([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ]);
            return $tokens['access_token'];
        }

        return null;
    }

    private function getValidAccessToken()
    {
        $tenant = tenant();
        tenancy()->initialize($tenant->id);

        $this->refreshAccessToken();

        $app = App::where('title', 'تطبيق Zoom')->first();
        return $app->access_token;
    }
}
