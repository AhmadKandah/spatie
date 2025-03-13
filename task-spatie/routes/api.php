<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\UserController;


//end


Route::apiResource('roles', RoleController::class);
Route::apiResource('permissions', PermissionController::class);



//ربط role with permissions
//ربط
Route::post('roles/{role}/assign-permission', [RoleController::class, 'assignPermission']);
//حذف
Route::post('roles/{role}/revoke-permission', [RoleController::class, 'revokePermission']);
//عدة صلاحيات مع دور واحد
Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions']);



//الراوتاب الخاصة باضافة مستخدم وبربط المستخدم مع دور ما
Route::post('users', [UserController::class, 'store']);
Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);
Route::post('users/{user}/remove-role', [UserController::class, 'removeRole']);
Route::post('users/{user}/sync-roles', [UserController::class, 'syncRoles']);