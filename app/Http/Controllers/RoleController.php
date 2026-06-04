<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use AuthorizesRequests ;
    
    public function index()
    {
        $this->authorize('role_view');
        $trashrole = Role::onlyTrashed()->count();
        $roles = Role::orderBy('created_at', 'desc')->get();
        return view('role.index', compact('roles','trashrole'));
    }

    public function create()
    {
        $this->authorize('role_create');
        // dd(auth()->user()->can('user_view'));
        $permissions = Permission::get();
        return view('role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);
            logUserActivity('Role', 'Created Role ' . $role->name, $role->id, 'Role');
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            DB::commit();
            return redirect()->route('role.index')->with('success', 'Role created successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating role: ' . $ex->getMessage());
        }
    }

    public function edit($id)
    {
        $this->authorize('role_edit');
        $role = Role::findOrFail($id);
        $permissions = Permission::get();
        return view('role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();
            logUserActivity('Role', 'Updated Role ' . $role->name, $role->id, 'Role');
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            DB::commit();
            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating role: ' . $ex->getMessage());
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);
            logUserActivity('Role', 'Deleted Role ' . $role->name, $role->id, 'Role');
            $role->delete();
            DB::commit();
            return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting role: ' . $ex->getMessage());
        }
    }
     public function trash()
    {
        $this->authorize('role_trash_view');
        $roles = Role::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('role.trash', compact('roles'));
    }

    public function restore($id)
    {
        try {
            $role = Role::withTrashed()->findOrFail($id);
            logUserActivity('Role', 'Deleted Role ' . $role->name, $role->id, 'Role');
            $role->restore();
            DB::commit();
            return redirect()->route('role.index')->with('success', 'Role restored successfully.');
        } catch (Exception $e) {
            Log::error('User restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring user: ' . $e->getMessage());
        }
    }
}
