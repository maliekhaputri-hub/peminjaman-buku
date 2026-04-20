<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = User::where('role', 'user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $members = $query->latest()->paginate(10);
        
        return view('admin.members.index', compact('members', 'search'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Display the specified member.
     */
    public function show(User $member)
    {
        if ($member->role !== 'user') {
            return redirect()->route('admin.members.index')
                ->with('error', 'Anggota tidak ditemukan!');
        }
        
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(User $member)
    {
        if ($member->role !== 'user') {
            return redirect()->route('admin.members.index')
                ->with('error', 'Anggota tidak ditemukan!');
        }
        
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, User $member)
    {
        if ($member->role !== 'user') {
            return redirect()->route('admin.members.index')
                ->with('error', 'Anggota tidak ditemukan!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil diperbarui!');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(User $member)
    {
        if ($member->role !== 'user') {
            return redirect()->route('admin.members.index')
                ->with('error', 'Anggota tidak ditemukan!');
        }

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }
}
