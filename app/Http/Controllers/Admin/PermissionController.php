<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Str;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'desc')->paginate(8);
        return view('admin.pages.permissions.list', compact('permissions'));
    }

    public function store(StorePermissionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $permission = Permission::create($validated);

        return response()->json([
            'success'    => true,
            'message'    => 'Permission created successfully!',
            'permission' => $permission
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $permission->update($validated);

        return response()->json([
            'success'    => true,
            'message'    => 'Permission updated successfully!',
            'permission' => $permission
        ]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();
        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully!'
        ]);
    }
}
