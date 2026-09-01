<?php

namespace App\Http\Controllers;

use App\Models\Industry;
use App\Models\PklMonitoring;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = PklMonitoring::with(['teacher', 'industry', 'student'])->orderBy('visit_date', 'desc');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $query->where('teacher_id', $teacher ? $teacher->id : null);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $query->where('industry_id', $industry ? $industry->id : null);
        }

        $monitorings = $query->paginate(15);
        $industries = Industry::all();

        return view('monitoring.index', compact('monitorings', 'industries'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $teacher = $user->teacher;
        $students = $teacher ? $teacher->students()->with('industry')->get() : Student::with('industry')->get();
        $industries = Industry::all();

        return view('monitoring.create', compact('students', 'industries'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'industry_id' => ['required', 'exists:industries,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'visit_date' => ['required', 'date'],
            'notes' => ['required', 'string'],
            'obstacles' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $teacher = $user->teacher;
        if (!$teacher && $user->isAdmin()) {
            // Pick first teacher or fallback
            $teacher = \App\Models\Teacher::first();
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoPath = Storage::disk('public')->putFile('monitorings', $file);
            
            // Copy directly to public/uploads/monitorings and public/storage/monitorings
            try {
                $filename = basename($photoPath);
                $uploadDir = public_path('uploads/monitorings');
                if (!file_exists($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                @copy($file->getRealPath(), $uploadDir . '/' . $filename);

                $storageDir = public_path('storage/monitorings');
                if (!file_exists($storageDir)) {
                    @mkdir($storageDir, 0755, true);
                }
                @copy($file->getRealPath(), $storageDir . '/' . $filename);
            } catch (\Exception $e) {}
        }

        PklMonitoring::create([
            'teacher_id' => $teacher->id,
            'industry_id' => $request->industry_id,
            'student_id' => $request->student_id,
            'visit_date' => $request->visit_date,
            'notes' => $request->notes,
            'obstacles' => $request->obstacles,
            'recommendations' => $request->recommendations,
            'photo' => $photoPath,
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Catatan monitoring PKL berhasil ditambahkan.');
    }

    private function getMonitoringData(Request $request)
    {
        $user = Auth::user();
        $query = PklMonitoring::with(['teacher', 'industry', 'student'])->orderBy('visit_date', 'desc');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $query->where('teacher_id', $teacher ? $teacher->id : null);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $query->where('industry_id', $industry ? $industry->id : null);
        }

        if ($request->filled('industry_id')) {
            $query->where('industry_id', $request->industry_id);
        }

        return $query->get();
    }

    public function exportExcel(Request $request)
    {
        $monitorings = $this->getMonitoringData($request);
        $filename = 'catatan-monitoring-smkn10-' . date('Ymd-His') . '.xls';

        return response()->streamDownload(function () use ($monitorings) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head>';
            echo '<body><table border="1">';
            echo '<tr style="background-color:#d97706;color:#ffffff;font-weight:bold;">';
            echo '<th>No</th><th>Tanggal Kunjungan</th><th>Industri Mitra</th><th>Guru Pembimbing</th><th>Siswa Terkait</th><th>Catatan Bimbingan</th><th>Kendala Lapangan</th><th>Rekomendasi Tindak Lanjut</th>';
            echo '</tr>';
            foreach ($monitorings as $index => $item) {
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . $item->visit_date . '</td>';
                echo '<td>' . htmlspecialchars($item->industry->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->teacher->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->name ?? 'Semua Siswa') . '</td>';
                echo '<td>' . htmlspecialchars($item->notes ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->obstacles ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->recommendations ?? '-') . '</td>';
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
        $monitorings = $this->getMonitoringData($request);
        $user = Auth::user();

        $teacher = null;
        if ($user->isGuru() && $user->teacher) {
            $teacher = $user->teacher;
        } else {
            $teacher = $monitorings->first()?->teacher ?? \App\Models\Teacher::first();
        }

        return view('exports.monitoring-pdf', compact('monitorings', 'teacher'));
    }
}
