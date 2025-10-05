<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\level;
use App\Models\Question;
use App\Models\user;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
      public function dashboard()
    {

        $usersCount = User::count();
        $adminsCount = Admin::count();
        $levelCount = level::count();
        $questionCount = Question::count();
        $topSchools = User::query()
            ->selectRaw('school, COUNT(*) as users_count')
            ->whereNotNull('school')
            ->groupBy('school')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();


        $lowSchools = User::query()
            ->selectRaw('school, COUNT(*) as users_count')
            ->whereNotNull('school')
            ->groupBy('school')
            ->orderBy('users_count')
            ->take(5)
            ->get();

        return view('admin.pages.index', compact(
            'usersCount',
            'adminsCount',
            'questionCount',
            'levelCount',
            'topSchools',
            'lowSchools'
        ));
    }

    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(8);
        $roles  = Role::all();

        return view('admin.pages.admin.list', compact('admins', 'roles'));
    }


    public function store(CreateAdminRequest $request)
    {
        $validated = $request->validated();

        $admin = Admin::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => Hash::make($validated['password']),
            'role_id'   => $validated['role_id'],
            'status'    => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'admin'   => $admin->load('role'),
        ]);
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $validated = $request->validated();


        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'success' => true,
            'admin'   => $admin->load('role'),
        ]);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully.',
        ]);
    }
}
