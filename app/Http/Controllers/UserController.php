<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,manajemen,pimpinan',
            'foto' => 'nullable|image|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['aktif'] = true;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('users', 'public');
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manajemen,pimpinan',
            'aktif' => 'boolean',
            'foto' => 'nullable|image|max:2048',
        ]);

        $validated['aktif'] = $request->has('aktif');

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = $request->file('foto')->store('users', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('password123')]);
        return back()->with('success', 'Password berhasil direset ke: password123');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['aktif' => !$user->aktif]);
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
