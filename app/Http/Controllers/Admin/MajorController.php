<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $query = Major::withCount('students')->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        $majors = $query->paginate(15);

        return view('admin.majors.index', compact('majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:majors,name'],
            'code' => ['required', 'string', 'max:50', 'unique:majors,code'],
        ]);

        Major::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Major $major)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:majors,name,' . $major->id],
            'code' => ['required', 'string', 'max:50', 'unique:majors,code,' . $major->id],
        ]);

        $major->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return redirect()->route('admin.majors.index')->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        if ($major->students()->exists()) {
            return redirect()->route('admin.majors.index')->with('error', 'Jurusan tidak dapat dihapus karena memiliki siswa yang terdaftar.');
        }

        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Data jurusan berhasil dihapus.');
    }
}
