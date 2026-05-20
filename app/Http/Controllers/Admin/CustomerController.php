<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search filtering
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'status' => 'required|in:active,pending,restricted',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'postal_code' => $validated['postal_code'],
            'address1' => $validated['address1'],
            'address2' => $validated['address2'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.customers.index')->with('success', __('Customer created successfully.'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($customer->id)],
            'phone' => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'status' => 'required|in:active,pending,restricted',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'postal_code' => $validated['postal_code'],
            'address1' => $validated['address1'],
            'address2' => $validated['address2'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', __('Customer updated successfully.'));
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', __('Customer deleted successfully.'));
    }
}
