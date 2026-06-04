<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('user_view');
        $trashuser = User::onlyTrashed()->count();
        $users = User::orderBy('created_at', 'desc')->with('role')->get();
        return view('user.index', compact('users', 'trashuser'));
    }

    public function create()
    {
        $this->authorize('user_create');
        $roles = Role::all();
        $contractors = Contractor::where('status', 'active')->get();
        return view('user.create', compact('roles', 'contractors'));
    }

    public function store(Request $request)
    {

        // return $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:3',
            'status' => 'required|in:active,inactive',
            'department' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',

            'is_contractor' => 'nullable|boolean',
            'contractor_id' => $request->has('is_contractor') ? 'required|exists:contractors,id' : 'nullable',
        ]);

        try {
            $user = new User();
            logUserActivity('User', 'Create User ' . $user->name, $user->id, 'User');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->department = $request->department;
            $user->password = Hash::make($request->password);

            $user->is_contractor = $request->has('is_contractor');
            $user->contractor_id = $request->has('is_contractor') ? $request->contractor_id : null;
            $user->save();
            // $user->assignRole($request->role);
            $user->syncRoles($request->roles);

            //SEND NOTIFICATION CODE START FROM HERE =================================================
                sendPermissionNotification(
                    'user_create',                   // permission
                    'User',                          // type
                    'New User Created',              // subject
                    'A new user "' . $user->name . '" has been created.', // message
                    route('user.index')              // optional URL
                );
            //END=======================================================================================
            // dd($user);
            return redirect()->route('user.index')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('user_edit');
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:active,inactive',
            'department' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'password' => 'nullable|string|min:3',
        ]);
        try {
            $user = User::findOrFail($id);
            logUserActivity('User', 'Update User ' . $user->name, $user->id, 'User');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->department = $request->department;
            // $user->role_id = $request->role_id;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
            $user->syncRoles($request->roles);
            return redirect()->route('user.index')->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            logUserActivity('User', 'Deleted user ' . $user->name, $user->id, 'User');
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('user_trash_view');
        $users = User::onlyTrashed()->orderBy('created_at', 'desc')->with('role')->get();
        return view('user.trash', compact('users'));
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
            return redirect()->route('user.index')->with('success', 'User restored successfully.');
        } catch (Exception $e) {
            Log::error('User restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring user: ' . $e->getMessage());
        }
    }

}
