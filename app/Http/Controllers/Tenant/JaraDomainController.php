<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class JaraDomainController extends Controller
{

    protected string $username = 'devahmedismael';
    protected string $token = 'de789604c733977392af2ce6a3f8b0ebf07896a8';

    public function check_availability(Request $request)
    {
        $request->validate([
            'domainNames' => 'required|array',
            'domainNames.*' => 'string',
        ]);

        $domains = $request->input('domainNames');

        $client = new Client(['base_uri' => 'https://api.name.com/v4/']);
        try {
            $response = $client->post('https://api.name.com/v4/domains:checkAvailability', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->token}"),
                    'Content-Type' => 'application/json',
                ],
                'json' => ['domainNames' => $domains],
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            return response()->json($responseBody, 200);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return response()->json(
                    json_decode($e->getResponse()->getBody()->getContents(), true),
                    $e->getResponse()->getStatusCode()
                );
            }

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
