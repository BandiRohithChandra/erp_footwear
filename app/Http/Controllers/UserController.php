<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $managers = User::all();
        $countries = config('countries.countries');
        return view('users.create', compact('roles', 'managers', 'countries'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'manager_id' => 'nullable|exists:users,id',
            'is_remote' => 'boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'region' => 'nullable|in:saudi_arabia,other',
            'country' => 'nullable|string|in:' . implode(',', array_keys(config('countries.countries'))),
        ];

        if ($request->input('region') === 'saudi_arabia') {
            $rules['iqama_number'] = 'required|string|max:255';
            $rules['iqama_expiry_date'] = 'required|date';
            $rules['health_card_number'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'manager_id' => $validated['manager_id'],
            'is_remote' => $validated['is_remote'] ?? false,
            'region' => $validated['region'] ?? null,
            'iqama_number' => $validated['iqama_number'] ?? null,
            'iqama_expiry_date' => $validated['iqama_expiry_date'] ?? null,
            'health_card_number' => $validated['health_card_number'] ?? null,
            'country' => $validated['country'] ?? null,
        ]);

        $user->assignRole($validated['roles']);

        return redirect()->route('users.index')->with('success', __('User created successfully!'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $managers = User::where('id', '!=', $user->id)->get(); // Exclude the user being edited
        $countries = config('countries.countries');
        return view('users.edit', compact('user', 'roles', 'managers', 'countries'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'manager_id' => 'nullable|exists:users,id',
            'is_remote' => 'boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'region' => 'nullable|in:saudi_arabia,other',
            'country' => 'nullable|string|in:' . implode(',', array_keys(config('countries.countries'))),
        ];

        if ($request->input('region') === 'saudi_arabia') {
            $rules['iqama_number'] = 'required|string|max:255';
            $rules['iqama_expiry_date'] = 'required|date';
            $rules['health_card_number'] = 'required|string|max:255'; // Fixed syntax error
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'manager_id' => $validated['manager_id'],
            'is_remote' => $validated['is_remote'] ?? false,
            'region' => $validated['region'] ?? null,
            'iqama_number' => $validated['iqama_number'] ?? null,
            'iqama_expiry_date' => $validated['iqama_expiry_date'] ?? null,
            'health_card_number' => $validated['health_card_number'] ?? null,
            'country' => $validated['country'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')->with('success', __('User updated successfully!'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', __('You cannot delete your own account!'));
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', __('User deleted successfully!'));
    }
}