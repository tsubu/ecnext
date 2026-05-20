<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff members.
     */
    public function index()
    {
        $staff = Admin::with('role')->latest()->paginate(15);
        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $roles = AdminRole::all();
        return view('admin.staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'admin_role_id' => 'required|exists:admin_roles,id',
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'admin_role_id' => $validated['admin_role_id'],
        ]);

        return redirect()->route('admin.staff.index')->with('success', __('Staff member created successfully.'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Admin $staff)
    {
        $roles = AdminRole::all();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, Admin $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($staff->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'admin_role_id' => 'required|exists:admin_roles,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'admin_role_id' => $validated['admin_role_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')->with('success', __('Staff member updated successfully.'));
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Admin $staff)
    {
        // Prevent deleting yourself
        if (auth('admin')->id() === $staff->id) {
            return redirect()->back()->with('error', __('You cannot delete your own account.'));
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', __('Staff member deleted successfully.'));
    }
}
