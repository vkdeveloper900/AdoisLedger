<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%"))
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalUsers    = User::count();
        $activeUsers   = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        return view('settings.users.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    public function create()
    {
        return view('settings.users.form', ['user' => null]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->only('name', 'email', 'phone');
        $data['password']  = Hash::make($request->password);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);

        return redirect()->route('settings.users.show', $user)->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('settings.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('settings.users.form', compact('user'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->only('name', 'email', 'phone');
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('settings.users.show', $user)->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('settings.users.index')->with('success', 'User deleted.');
    }
}
