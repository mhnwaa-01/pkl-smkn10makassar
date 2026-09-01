<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Industry;
use App\Models\Journal;
use App\Models\PklEvaluation;
use App\Models\PklMonitoring;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $stats = [];

        if ($user->isAdmin()) {
            $stats['total_students'] = Student::count();
            $stats['total_teachers'] = Teacher::count();
            $stats['total_industries'] = Industry::count();
            $stats['today_attendance'] = Attendance::where('date', $today)->count();
            $stats['pending_journals'] = Journal::where('status', 'pending')->count();
            $stats['total_evaluations'] = PklEvaluation::count();
            $recent_journals = Journal::with(['student.industry'])->latest()->take(5)->get();
            $recent_attendances = Attendance::with('student')->where('date', $today)->latest()->take(5)->get();
            return view('dashboard.admin', compact('stats', 'recent_journals', 'recent_attendances'));

        } elseif ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $stats['my_students'] = count($studentIds);
            $stats['today_attendance'] = Attendance::whereIn('student_id', $studentIds)->where('date', $today)->count();
            $stats['pending_journals'] = Journal::whereIn('student_id', $studentIds)->where('status', 'pending')->count();
            $stats['total_monitorings'] = $teacher ? PklMonitoring::where('teacher_id', $teacher->id)->count() : 0;
            $recent_journals = Journal::with('student')->whereIn('student_id', $studentIds)->latest()->take(5)->get();
            $recent_monitorings = $teacher ? PklMonitoring::with('industry')->where('teacher_id', $teacher->id)->latest()->take(5)->get() : collect();
            return view('dashboard.guru', compact('stats', 'recent_journals', 'recent_monitorings'));

        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $stats['intern_students'] = count($studentIds);
            $stats['today_attendance'] = Attendance::whereIn('student_id', $studentIds)->where('date', $today)->count();
            $stats['pending_journals'] = Journal::whereIn('student_id', $studentIds)->where('status', 'pending')->count();
            $stats['evaluated_students'] = PklEvaluation::whereIn('student_id', $studentIds)->count();
            $recent_journals = Journal::with('student')->whereIn('student_id', $studentIds)->latest()->take(5)->get();
            return view('dashboard.industri', compact('stats', 'recent_journals'));

        } else { // Siswa
            $student = $user->student;
            $todayAttendance = $student ? Attendance::where('student_id', $student->id)->where('date', $today)->first() : null;
            $totalJournals = $student ? Journal::where('student_id', $student->id)->count() : 0;
            $approvedJournals = $student ? Journal::where('student_id', $student->id)->where('status', 'approved')->count() : 0;
            $evaluation = $student ? PklEvaluation::where('student_id', $student->id)->first() : null;
            $recent_journals = $student ? Journal::where('student_id', $student->id)->latest()->take(5)->get() : collect();
            return view('dashboard.siswa', compact('todayAttendance', 'totalJournals', 'approvedJournals', 'evaluation', 'recent_journals'));
        }
    }
}
