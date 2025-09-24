<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('created_at', 'desc')->paginate(8);
        $permissions = Permission::all();
        return view('admin.pages.roles.list', compact('roles','permissions'));
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null
        ]);

        if(!empty($validated['permissions'])){
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json([
            'success'=> true,
            'role'   => $role->load('permissions')
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $validated = $request->validated();

        $role->update([
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'role'    => $role->load('permissions')
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['success'=>true]);
    }
}
