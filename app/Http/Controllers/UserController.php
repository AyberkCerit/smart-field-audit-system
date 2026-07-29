<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        
        $role = $validated['role'];
        unset($validated['role']);
        
        $user = User::create($validated);
        $user->assignRole($role);
        
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        
        if (isset($validated['role'])) {
            $role = $validated['role'];
            unset($validated['role']);
            $user->syncRoles([$role]);
        }
        
        // If password is provided, it will be hashed by the model cast.
        // If not provided, we remove it from the array so it's not updated.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }
        
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
