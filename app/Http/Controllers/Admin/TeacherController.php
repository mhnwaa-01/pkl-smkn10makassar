<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'students'])->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $teachers = $query->paginate(15);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => ['nullable', 'string', 'unique:teachers,nip'],
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string'],
        ]);

        // 1. Create Teacher User Account
        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->username . '@guru.smkn10.sch.id',
            'password' => $request->password,
            'role' => 'guru',
        ]);

        // 2. Create Teacher Profile
        Teacher::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru pembimbing dan akun login berhasil ditambahkan.');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'string', 'unique:teachers,nip,' . $teacher->id],
            'phone' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $teacher->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'phone' => $request->phone,
        ]);

        $user = $teacher->user;
        if ($user) {
            $userUpdate = ['name' => $request->name];
            if ($request->filled('password')) {
                $userUpdate['password'] = $request->password;
            }
            $user->update($userUpdate);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru pembimbing berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $user = $teacher->user;
        $teacher->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru pembimbing dan akun login berhasil dihapus.');
    }
}
