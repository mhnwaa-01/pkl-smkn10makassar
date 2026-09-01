<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $setting = AttendanceSetting::getSetting();
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');
        $allowedOutTime = $setting->check_out_early_time ?: $setting->check_out_time;
        $isCheckOutLocked = ($nowTime < $allowedOutTime);

        if ($user->isSiswa()) {
            $student = $user->student;
            $todayAttendance = Attendance::where('student_id', $student->id)
                ->where('date', $today)
                ->first();
            $attendances = Attendance::where('student_id', $student->id)
                ->orderBy('date', 'desc')
                ->paginate(15);

            return view('attendance.siswa', compact('setting', 'todayAttendance', 'attendances', 'student', 'isCheckOutLocked', 'allowedOutTime', 'nowTime'));
        }

        // Admin, Guru, Industri
        $query = Attendance::with(['student.industry', 'student.teacher', 'student.major'])->orderBy('date', 'desc');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('check_in_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        $attendances = $query->paginate(20);

        return view('attendance.index', compact('attendances', 'setting'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }

        $student = $user->student;
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');

        $existing = Attendance::where('student_id', $student->id)->where('date', $today)->first();
        if ($existing && $existing->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan presensi datang hari ini.');
        }

        $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        $setting = AttendanceSetting::getSetting();

        // Calculate late status
        $status = ($nowTime <= $setting->check_in_late_time) ? 'Tepat Waktu' : 'Terlambat';

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendances', 'public');
        }

        Attendance::updateOrCreate(
            ['student_id' => $student->id, 'date' => $today],
            [
                'check_in_time' => $nowTime,
                'check_in_status' => $status,
                'check_in_notes' => $request->input('notes'),
                'check_in_photo' => $photoPath,
                'location' => $request->input('location'),
            ]
        );

        $msg = ($status === 'Tepat Waktu')
            ? 'Presensi Datang berhasil dicatat: Tepat Waktu (' . $nowTime . ')'
            : 'Presensi Datang berhasil dicatat: Terlambat (' . $nowTime . ') (Batas jam masuk: ' . substr($setting->check_in_late_time, 0, 5) . ')';

        return redirect()->route('journals.index')->with('success', $msg);
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }

        $student = $user->student;
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');

        $attendance = Attendance::where('student_id', $student->id)->where('date', $today)->first();
        if (!$attendance || !$attendance->check_in_time) {
            return back()->with('error', 'Anda harus melakukan presensi datang terlebih dahulu!');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        $setting = AttendanceSetting::getSetting();
        $allowedOutTime = $setting->check_out_early_time ?: $setting->check_out_time;

        // Enforce lock if current time is before allowed checkout time
        if ($nowTime < $allowedOutTime) {
            return back()->with('error', 'Presensi Pulang masih terkunci! Anda baru dapat melakukan presensi pulang mulai pukul ' . substr($allowedOutTime, 0, 5) . ' WITA.');
        }

        $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        // Calculate early status
        $status = ($nowTime < $setting->check_out_time) ? 'Pulang Cepat' : 'Tepat Waktu';

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendances', 'public');
        }

        $location = $request->input('location') ?: $attendance->location;

        $attendance->update([
            'check_out_time' => $nowTime,
            'check_out_status' => $status,
            'check_out_notes' => $request->input('notes'),
            'check_out_photo' => $photoPath,
            'location' => $location,
        ]);

        $msg = ($status === 'Tepat Waktu')
            ? 'Presensi Pulang berhasil dicatat: Tepat Waktu (' . $nowTime . ')'
            : 'Presensi Pulang berhasil dicatat: Pulang Cepat (' . $nowTime . ') (Jam pulang normal: ' . substr($setting->check_out_time, 0, 5) . ')';

        return redirect()->route('attendance.index')->with('success', $msg);
    }

    private function getAttendanceData(Request $request)
    {
        $user = Auth::user();

        if ($user->isSiswa()) {
            return Attendance::with(['student.industry', 'student.teacher', 'student.major'])
                ->where('student_id', $user->student->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        $query = Attendance::with(['student.industry', 'student.teacher', 'student.major'])->orderBy('date', 'desc');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('check_in_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function exportExcel(Request $request)
    {
        $attendances = $this->getAttendanceData($request);
        $filename = 'rekap-presensi-smkn10-' . date('Ymd-His') . '.xls';

        return response()->streamDownload(function () use ($attendances) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head>';
            echo '<body><table border="1">';
            echo '<tr style="background-color:#2563eb;color:#ffffff;font-weight:bold;">';
            echo '<th>No</th><th>Tanggal</th><th>Nama Siswa</th><th>NISN</th><th>Kelas</th><th>Jurusan</th><th>Industri</th><th>Jam Masuk</th><th>Status Masuk</th><th>Jam Pulang</th><th>Status Pulang</th><th>Lama Kerja</th><th>Lokasi</th><th>Catatan Masuk</th><th>Catatan Pulang</th>';
            echo '</tr>';
            foreach ($attendances as $index => $item) {
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . $item->date . '</td>';
                echo '<td>' . htmlspecialchars($item->student->name ?? '-') . '</td>';
                echo '<td>\'' . ($item->student->nisn ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->class_name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->major->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->industry->name ?? '-') . '</td>';
                echo '<td>' . ($item->check_in_time ? $item->check_in_time . ' WITA' : '-') . '</td>';
                echo '<td>' . ($item->check_in_status ?? '-') . '</td>';
                echo '<td>' . ($item->check_out_time ? $item->check_out_time . ' WITA' : '-') . '</td>';
                echo '<td>' . ($item->check_out_status ?? '-') . '</td>';
                echo '<td>' . ($item->work_duration ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->location ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->check_in_notes ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->check_out_notes ?? '-') . '</td>';
                echo '</tr>';
            }
            echo '</table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $attendances = $this->getAttendanceData($request);
        $filterDate = $request->input('date');
        $user = Auth::user();

        $teacher = null;
        $industry = null;

        if ($user->isSiswa() && $user->student) {
            $teacher = $user->student->teacher;
            $industry = $user->student->industry;
        } elseif ($user->isGuru() && $user->teacher) {
            $teacher = $user->teacher;
            $industry = $attendances->first()?->student?->industry;
        } elseif ($user->isIndustri() && $user->industry) {
            $industry = $user->industry;
            $teacher = $attendances->first()?->student?->teacher ?? \App\Models\Teacher::first();
        } else {
            $teacher = $attendances->first()?->student?->teacher ?? \App\Models\Teacher::first();
            $industry = $attendances->first()?->student?->industry ?? \App\Models\Industry::first();
        }

        return view('exports.attendance-pdf', compact('attendances', 'filterDate', 'teacher', 'industry'));
    }
}
