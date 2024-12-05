<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\User\RoleRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create(['name' => $request->input('name')]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return response()->json(['message' => 'تم إنشاء الدور الوظيفي بنجاح.', 'role' => $role], 201);
    }

    public function show(string $id)
    {
        $role = Role::findById($id);
        return response()->json($role->load('permissions'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        if ($role->name !== $request->input('name')) {
            $role->update(['name' => $request->input('name')]);

        }
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return response()->json($role, 200);
    }

    public function destroy(string $id)

    {


    }

}
