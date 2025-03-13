<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
   'name'=>'required |unique:roles,name']);
   $role=Role::create(['name'=>$request->name]);
        return response()->json($role, 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role=Role::findOrFail($id);
        return response()->json($role);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $role=Role::findOrFail($id);
        $request->validate([   'name'=>'required |unique:roles,name']);
        $role->update(['name' => $request->name]);
        return response()->json($role);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }


    // ربط صلاحية بدور ما 
    public function assignPermission(Request $request, $roleId)
    {
       
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::findOrFail($roleId);
        $permission = Permission::where('name', $request->permission)->firstOrFail();

        $role->givePermissionTo($permission);

        return response()->json([
            'message' => 'Permission assigned to role successfully',
            'role' => $role->load('permissions'),
        ]);
    }

 //حذف صلاحية من دور ما 
    public function revokePermission(Request $request, $roleId)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::findOrFail($roleId);
        $permission = Permission::where('name', $request->permission)->firstOrFail();

        $role->revokePermissionTo($permission);

        return response()->json([
            'message' => 'Permission revoked from role successfully',
            'role' => $role->load('permissions'),
        ]);
    }
// (اختياري) مزامنة عدة صلاحيات مع دور
public function syncPermissions(Request $request, $roleId)
{
    $request->validate([
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,name',
    ]);

    $role = Role::findOrFail($roleId);
    $role->syncPermissions($request->permissions);

    return response()->json([
        'message' => 'Permissions synced with role successfully',
        'role' => $role->load('permissions'),
    ]);
}
    
}

