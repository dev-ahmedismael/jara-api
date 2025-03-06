<?php

namespace App\Http\Controllers\Tenant\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.unique' => 'يوجد دور وظيفي بهذا الاسم بالفعل.',
            'name.required' => 'اسم الدور مطلوب.',
        ]);

         $role = Role::create(['name' => $request->name]);

         if (!empty($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'تم إنشاء الدور بنجاح.',
            'role' => $role,
        ], 201);
    }

    public function index(Request $request)
    {
        $roles = Role::latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $roles], 200);
    }

    public function all_roles(Request $request)
    {
        $roles = Role::latest()->get();
        return response()->json(['data' => $roles], 200);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json(['data' => $role], 200);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.unique' => 'يوجد دور وظيفي بهذا الاسم بالفعل.',
            'name.required' => 'اسم الدور مطلوب.',
        ]);

        $role->update(['name' => $request->name]);

        if (!empty($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'تم تحديث الدور بنجاح.',
            'role' => $role,
        ], 200);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'تم حذف الدور بنجاح.'], 200);
    }
}

