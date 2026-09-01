<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Major;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'teacher', 'industry', 'major'])->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
        }

        $students = $query->paginate(15);
        $teachers = Teacher::all();
        $industries = Industry::all();
        $majors = Major::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'teachers', 'industries', 'majors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => ['required', 'string', 'unique:students,nisn'],
            'name' => ['required', 'string', 'max:150'],
            'class_name' => ['required', 'string', 'max:50'],
            'major_id' => ['required', 'exists:majors,id'],
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'industry_id' => ['nullable', 'exists:industries,id'],
            'phone' => ['nullable', 'string'],
        ]);

        // 1. Create User Login Account
        $user = User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->username . '@siswa.smkn10.sch.id',
            'password' => $request->password,
            'role' => 'siswa',
        ]);

        // 2. Create Student Profile
        Student::create([
            'user_id' => $user->id,
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class_name' => $request->class_name,
            'major_id' => $request->major_id,
            'teacher_id' => $request->teacher_id,
            'industry_id' => $request->industry_id,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa dan akun login siswa berhasil dibuat.');
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'class_name' => ['required', 'string', 'max:50'],
            'major_id' => ['required', 'exists:majors,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
            'industry_id' => ['nullable', 'exists:industries,id'],
            'phone' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $student->update([
            'name' => $request->name,
            'class_name' => $request->class_name,
            'major_id' => $request->major_id,
            'teacher_id' => $request->teacher_id,
            'industry_id' => $request->industry_id,
            'phone' => $request->phone,
        ]);

        $user = $student->user;
        if ($user) {
            $userUpdate = ['name' => $request->name];
            if ($request->filled('password')) {
                $userUpdate['password'] = $request->password;
            }
            $user->update($userUpdate);
        }

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $user = $student->user;
        $student->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.students.index')->with('success', 'Data siswa dan akun login berhasil dihapus.');
    }
}
