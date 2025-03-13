<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // إنشاء مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    // تعيين دور لمستخدم
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role)->firstOrFail();

        $user->assignRole($role);

        return response()->json([
            'message' => 'Role assigned to user successfully',
            'user' => $user->load('roles'),
        ]);
    }

    // (اختياري) إزالة دور من مستخدم
    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role)->firstOrFail();

        $user->removeRole($role);

        return response()->json([
            'message' => 'Role removed from user successfully',
            'user' => $user->load('roles'),
        ]);
    }

    // (اختياري) مزامنة الأدوار لمستخدم
    public function syncRoles(Request $request, $userId)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User      ::findOrFail($userId);
        $user->syncRoles($request->roles);

        return response()->json([
            'message' => 'Roles synced with user successfully',
            'user' => $user->load('roles'),
        ]);
    }
}

