<?php

namespace App\Http\Controllers\Central\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Central\Tenant\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request) {
        $tenants = Tenant::filter($request)->latest()->paginate($request->query('per_page', 10));

        $tenants->map(function ($tenant) {
            $customer = $tenant->customers()
                ->wherePivot('role', 'customer')
                ->first();

            $tenant->license_document = $customer?->getFirstMediaUrl('license_documents'); // Get media URL
            $tenant->website_type = 'إستشارات';

            return $tenant;
        });

        return response()->json(['data' => $tenants]);

    }

    public function show(Request $request, $id) {
        $tenant = Tenant::findOrFail($id);

        return response()->json(['data' => $tenant]);
    }

    public function update(Request $request, string $tenant_id) {
        $validated = $request->validate([
            'is_active' => 'boolean',
            'bank_account_number' => 'string',
        ]);

        $tenant = Tenant::findOrFail($tenant_id);
        $tenant->update($validated);
        $tenant->save();
        return response()->json(['message' => 'تم التعديل بنجاح.'], 200);
    }
}
