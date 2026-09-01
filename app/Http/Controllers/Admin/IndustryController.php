<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index(Request $request)
    {
        $query = Industry::with(['user', 'students'])->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
        }

        $industries = $query->paginate(15);

        return view('admin.industries.index', compact('industries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'contact_person' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
        ]);

        // 1. Create Industry User Account
        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->username . '@industri.smkn10.sch.id',
            'password' => $request->password,
            'role' => 'industri',
        ]);

        // 2. Create Industry Profile
        Industry::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.industries.index')->with('success', 'Data industri dan akun login industri berhasil ditambahkan.');
    }

    public function update(Request $request, Industry $industry)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'contact_person' => ['required', 'string', 'max:150'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $industry->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        $user = $industry->user;
        if ($user) {
            $userUpdate = ['name' => $request->name];
            if ($request->filled('password')) {
                $userUpdate['password'] = $request->password;
            }
            $user->update($userUpdate);
        }

        return redirect()->route('admin.industries.index')->with('success', 'Data industri berhasil diperbarui.');
    }

    public function destroy(Industry $industry)
    {
        $user = $industry->user;
        $industry->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.industries.index')->with('success', 'Data industri dan akun login berhasil dihapus.');
    }
}
