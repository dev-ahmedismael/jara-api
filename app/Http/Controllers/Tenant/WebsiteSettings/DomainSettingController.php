<?php

namespace App\Http\Controllers\Tenant\WebsiteSettings;

use App\Http\Controllers\Controller;
use App\Models\Tenant\WebsiteSettings\DomainSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Models\Domain;

class DomainSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenant = tenant();

        $domain = $tenant->domains()->latest()->first();
        if (!$domain) {
            return response()->json(['message' => 'لا يوجد دومين خاص بموقعك.'], 404);
        }

        $formattedDomain = str_contains($domain, '.') ? $domain : "$domain.jara.site";

        return response()->json(['domain' => $formattedDomain], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
        ]);

        $this->check_subdomain_availability($request);
         $tenant = tenant();
        $domain = $tenant->domains()->latest()->first();

        if ($domain) {
            $domain->update([
                'domain' => $request->input('domain'),
                'tenant_id' => $tenant->id,
            ]);
        }

        if (!$domain) {
            Domain::create(['domain' => $request->input('domain'), 'tenant_id' => $tenant->id]);
        }

        return response()->json(['message' => 'تم تحديث الدومين بنجاح.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(DomainSetting $domainSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DomainSetting $domainSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DomainSetting $domainSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DomainSetting $domainSetting)
    {
        //
    }

    public function check_subdomain_availability (Request $request) {
        $request->validate(['domain' => 'required|string']);
        $isFound = Domain::where('domain', $request->input('domain'))->first();
        if ($isFound || Str::contains($request->input('domain'), '.') )  {
            return response()->json(['message' => 'الدومين الذي أدخلته مستخدم من قبل.'], 400);
        }
        return response()->json(['message' => 'الدومين متوفر.'], 200);
    }

    public function check_domain_availability (Request $request) {
        $request->validate(['domain' => 'required|string']);

        $domain = $request->input('domain');

        $response = Http::withBasicAuth(
            'devahmedismael',
             'de789604c733977392af2ce6a3f8b0ebf07896a8'
        )->post('https://api.name.com/v4/domains:checkAvailability', [
            'domainNames' => [$domain],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return response()->json(
                  $data
            , 200);
        }

        return response()->json(['message' => 'تعذر الاتصال بالخوادم في الوقت الحالي، يرجى المحاولة مرة أخرى في وقت لاحق.'], 500);
    }
}
