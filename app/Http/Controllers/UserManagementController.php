<?php
// File: app/Http/Controllers/UserManagementController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // TAMBAHAN INI
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::where('is_active', true)
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,kasir,cleaning',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,kasir,cleaning',
            'is_active' => 'required|boolean',
        ]);

        $user->update($request->only(['name', 'username', 'role', 'is_active']));

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'password123';
        $user->update(['password' => Hash::make($newPassword)]);

        return redirect()->back()
            ->with('success', 'Password berhasil direset! Password baru: ' . $newPassword);
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === Auth::id()) { // FIXED
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function profile()
    {
        $user = Auth::user(); // FIXED
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ]);

        // Gunakan User model langsung
        User::where('id', Auth::id())->update($request->only(['name', 'username']));

        return redirect()->back()
            ->with('success', 'Profile berhasil diupdate!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Ambil user dengan cara yang aman
        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai!']);
        }

        // Update password menggunakan User model langsung
        User::where('id', Auth::id())->update(['password' => Hash::make($request->password)]);

        return redirect()->back()
            ->with('success', 'Password berhasil diubah!');
    }
}
