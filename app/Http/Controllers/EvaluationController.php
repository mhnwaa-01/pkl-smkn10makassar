<?php

namespace App\Http\Controllers;

use App\Models\PklEvaluation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSiswa()) {
            $student = $user->student;
            $evaluation = PklEvaluation::with(['student.industry', 'student.major', 'evaluator'])->where('student_id', $student->id)->first();
            return view('evaluations.siswa', compact('student', 'evaluation'));
        }

        $query = Student::with(['industry', 'teacher', 'evaluation', 'major'])->orderBy('name');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('id', $studentIds);
        }

        $students = $query->paginate(15);

        return view('evaluations.index', compact('students'));
    }

    public function storeOrUpdate(Request $request, Student $student)
    {
        $user = Auth::user();
        if (!$user->isIndustri() && !$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $rules = [];
        if ($user->isIndustri() || $user->isAdmin()) {
            $rules['aspect_attitude'] = ['required', 'numeric', 'min:0', 'max:100'];
            $rules['aspect_technical'] = ['required', 'numeric', 'min:0', 'max:100'];
            $rules['aspect_managerial'] = ['required', 'numeric', 'min:0', 'max:100'];
        }
        if ($user->isGuru() || $user->isAdmin()) {
            $rules['aspect_report'] = ['required', 'numeric', 'min:0', 'max:100'];
            $rules['aspect_presentation'] = ['required', 'numeric', 'min:0', 'max:100'];
        }
        $rules['notes'] = ['nullable', 'string'];

        $request->validate($rules);

        $existing = PklEvaluation::where('student_id', $student->id)->first();

        $attitude = $request->input('aspect_attitude') ?? ($existing ? $existing->aspect_attitude : 0);
        $technical = $request->input('aspect_technical') ?? ($existing ? $existing->aspect_technical : 0);
        $managerial = $request->input('aspect_managerial') ?? ($existing ? $existing->aspect_managerial : 0);
        $report = $request->input('aspect_report') ?? ($existing ? $existing->aspect_report : 0);
        $presentation = $request->input('aspect_presentation') ?? ($existing ? $existing->aspect_presentation : 0);

        // Nilai akhir dan predikat hanya dihitung jika semua form sudah diisi (attitude, technical, managerial, report, dan presentation > 0)
        if ($attitude > 0 && $technical > 0 && $managerial > 0 && $report > 0 && $presentation > 0) {
            $finalScore = round(($attitude + $technical + $managerial + $report + $presentation) / 5, 2);
            $predicate = 'D';
            if ($finalScore >= 85) {
                $predicate = 'A';
            } elseif ($finalScore >= 75) {
                $predicate = 'B';
            } elseif ($finalScore >= 65) {
                $predicate = 'C';
            }
        } else {
            $finalScore = 0;
            $predicate = 'Belum Lengkap';
        }

        PklEvaluation::updateOrCreate(
            ['student_id' => $student->id],
            [
                'industry_id' => $student->industry_id ?? $user->industry->id ?? null,
                'evaluator_user_id' => $user->id,
                'aspect_attitude' => $attitude,
                'aspect_technical' => $technical,
                'aspect_managerial' => $managerial,
                'aspect_report' => $report,
                'aspect_presentation' => $presentation,
                'final_score' => $finalScore,
                'predicate' => $predicate,
                'notes' => $request->input('notes') ?? ($existing ? $existing->notes : null),
            ]
        );

        return back()->with('success', 'Penilaian PKL untuk ' . $student->name . ' telah berhasil disimpan.');
    }

    public function destroy(Student $student)
    {
        $user = Auth::user();
        if (!$user->isIndustri() && !$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        PklEvaluation::where('student_id', $student->id)->delete();

        return back()->with('success', 'Penilaian PKL untuk ' . $student->name . ' telah berhasil direset.');
    }

    private function getEvaluationsData()
    {
        $user = Auth::user();

        if ($user->isSiswa()) {
            return Student::with(['industry', 'teacher', 'evaluation', 'major'])
                ->where('id', $user->student->id)
                ->get();
        }

        $query = Student::with(['industry', 'teacher', 'evaluation', 'major'])->orderBy('name');

        if ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('id', $studentIds);
        }

        return $query->get();
    }

    public function exportExcel()
    {
        $students = $this->getEvaluationsData();
        $filename = 'rekap-penilaian-pkl-smkn10-' . date('Ymd-His') . '.xls';

        return response()->streamDownload(function () use ($students) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head>';
            echo '<body><table border="1">';
            echo '<tr style="background-color:#7c3aed;color:#ffffff;font-weight:bold;">';
            echo '<th>No</th><th>Nama Siswa</th><th>NISN</th><th>Kelas</th><th>Jurusan</th><th>Industri</th><th>Guru Pembimbing</th><th>Form 1 (Sikap)</th><th>Form 2 & 3 (Teknis)</th><th>Form 4 (Kewirausahaan)</th><th>Form 5 (Laporan)</th><th>Form 6 (Presentasi)</th><th>Nilai Akhir</th><th>Predikat</th><th>Catatan</th>';
            echo '</tr>';
            foreach ($students as $index => $st) {
                $ev = $st->evaluation;
                $isComplete = ($ev && $ev->aspect_attitude > 0 && $ev->aspect_technical > 0 && $ev->aspect_managerial > 0 && $ev->aspect_report > 0 && $ev->aspect_presentation > 0);

                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . htmlspecialchars($st->name) . '</td>';
                echo '<td>\'' . $st->nisn . '</td>';
                echo '<td>' . htmlspecialchars($st->class_name) . '</td>';
                echo '<td>' . htmlspecialchars($st->major->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($st->industry->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($st->teacher->name ?? '-') . '</td>';
                echo '<td>' . (($ev && $ev->aspect_attitude > 0) ? $ev->aspect_attitude : '-') . '</td>';
                echo '<td>' . (($ev && $ev->aspect_technical > 0) ? $ev->aspect_technical : '-') . '</td>';
                echo '<td>' . (($ev && $ev->aspect_managerial > 0) ? $ev->aspect_managerial : '-') . '</td>';
                echo '<td>' . (($ev && $ev->aspect_report > 0) ? $ev->aspect_report : '-') . '</td>';
                echo '<td>' . (($ev && $ev->aspect_presentation > 0) ? $ev->aspect_presentation : '-') . '</td>';
                echo '<td>' . ($isComplete ? $ev->final_score : '-') . '</td>';
                echo '<td>' . ($isComplete ? $ev->predicate : 'Belum Lengkap') . '</td>';
                echo '<td>' . htmlspecialchars($ev->notes ?? '-') . '</td>';
                echo '</tr>';
            }
            echo '</table></body></html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function exportPdf()
    {
        $students = $this->getEvaluationsData();
        $user = Auth::user();

        $teacher = null;
        $industry = null;

        if ($user->isSiswa() && $user->student) {
            $teacher = $user->student->teacher;
            $industry = $user->student->industry;
        } elseif ($user->isGuru() && $user->teacher) {
            $teacher = $user->teacher;
            $industry = $students->first()?->industry;
        } elseif ($user->isIndustri() && $user->industry) {
            $industry = $user->industry;
            $teacher = $students->first()?->teacher ?? \App\Models\Teacher::first();
        } else {
            $teacher = $students->first()?->teacher ?? \App\Models\Teacher::first();
            $industry = $students->first()?->industry ?? \App\Models\Industry::first();
        }

        return view('exports.evaluations-pdf', compact('students', 'teacher', 'industry'));
    }
}
