<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $query = Role::query();

        if (request()->filled('search')) {
            $search = request()->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->latest()->paginate(15)->appends(request()->all());

        return view('roles.index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 15);
    }

    public function create()
    {
        $role = null;

        if (request()->filled('id')) {
            $role = Role::where('id', request()->input('id'))->first();
        }

        $permission = Permission::get();

        return view('roles.create', compact('role', 'permission'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $role = Role::create($input);

        if ($request->input('permission')) {
            $permissionsID = array_map(
                function ($value) {
                    return (int)$value;
                },
                $request->input('permission')
            );
            $role->syncPermissions($permissionsID);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit(Role $role)
    {
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->name = $request->input('name');
        $role->save();

        if ($request->input('permission')) {
            $permissionsID = array_map(
                function ($value) {
                    return (int)$value;
                },
                $request->input('permission')
            );
            $role->syncPermissions($permissionsID);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role edited successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
