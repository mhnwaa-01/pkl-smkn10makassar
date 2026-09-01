<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Journal::with(['student.industry', 'student.teacher', 'verifier'])->orderBy('date', 'desc');

        if ($user->isSiswa()) {
            $student = $user->student;
            $query->where('student_id', $student->id);
        } elseif ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $journals = $query->paginate(15);

        return view('journals.index', compact('journals'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }
        return view('journals.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isSiswa()) {
            abort(403);
        }

        $student = $user->student;

        $request->validate([
            'date' => ['required', 'date'],
            'activity_title' => ['required', 'string', 'max:255'],
            'activity_description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'], // max 5MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'journal_' . $student->id . '_' . time() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
            $uploadDir = public_path('uploads/journals');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            $photoPath = 'uploads/journals/' . $filename;

            // Also copy to storage/app/public and public/storage
            try {
                $storageDir = storage_path('app/public/journals');
                if (!file_exists($storageDir)) {
                    @mkdir($storageDir, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $storageDir . '/' . $filename);

                $pubStorageDir = public_path('storage/journals');
                if (!file_exists($pubStorageDir)) {
                    @mkdir($pubStorageDir, 0755, true);
                }
                @copy($uploadDir . '/' . $filename, $pubStorageDir . '/' . $filename);
            } catch (\Exception $e) {}
        }

        Journal::create([
            'student_id' => $student->id,
            'date' => $request->date,
            'activity_title' => $request->activity_title,
            'activity_description' => $request->activity_description,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('journals.index')->with('success', 'Jurnal harian berhasil ditambahkan dan menunggu verifikasi pembimbing industri.');
    }

    public function verify(Request $request, Journal $journal)
    {
        $user = Auth::user();
        if (!$user->isIndustri() && !$user->isAdmin()) {
            abort(403, 'Hanya pembimbing industri dan admin yang dapat memverifikasi jurnal.');
        }

        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        $journal->update([
            'status' => $request->status,
            'verification_notes' => $request->verification_notes,
            'verified_at' => now(),
            'verified_by' => $user->id,
        ]);

        $msg = ($request->status === 'approved')
            ? 'Jurnal berhasil disetujui.'
            : 'Jurnal telah ditolak dengan catatan.';

        return back()->with('success', $msg);
    }

    private function getJournalsData(Request $request)
    {
        $user = Auth::user();
        $query = Journal::with(['student.industry', 'student.teacher', 'student.major', 'verifier'])->orderBy('date', 'desc');

        if ($user->isSiswa()) {
            $student = $user->student;
            $query->where('student_id', $student->id);
        } elseif ($user->isGuru()) {
            $teacher = $user->teacher;
            $studentIds = $teacher ? $teacher->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        } elseif ($user->isIndustri()) {
            $industry = $user->industry;
            $studentIds = $industry ? $industry->students()->pluck('id') : collect();
            $query->whereIn('student_id', $studentIds);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    public function exportExcel(Request $request)
    {
        $journals = $this->getJournalsData($request);
        $filename = 'rekap-jurnal-smkn10-' . date('Ymd-His') . '.xls';

        return response()->streamDownload(function () use ($journals) {
            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
            echo '<head><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head>';
            echo '<body><table border="1">';
            echo '<tr style="background-color:#059669;color:#ffffff;font-weight:bold;">';
            echo '<th>No</th><th>Tanggal</th><th>Nama Siswa</th><th>NISN</th><th>Kelas</th><th>Jurusan</th><th>Industri</th><th>Judul Kegiatan</th><th>Deskripsi Kegiatan</th><th>Status</th><th>Catatan Pembimbing</th>';
            echo '</tr>';
            foreach ($journals as $index => $item) {
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . $item->date . '</td>';
                echo '<td>' . htmlspecialchars($item->student->name ?? '-') . '</td>';
                echo '<td>\'' . ($item->student->nisn ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->class_name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->major->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->student->industry->name ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->activity_title ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->activity_description ?? '-') . '</td>';
                echo '<td>' . ucfirst($item->status ?? '-') . '</td>';
                echo '<td>' . htmlspecialchars($item->verification_notes ?? '-') . '</td>';
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
        $journals = $this->getJournalsData($request);
        $filterStatus = $request->input('status');
        $user = Auth::user();

        $teacher = null;
        $industry = null;

        if ($user->isSiswa() && $user->student) {
            $teacher = $user->student->teacher;
            $industry = $user->student->industry;
        } elseif ($user->isGuru() && $user->teacher) {
            $teacher = $user->teacher;
            $industry = $journals->first()?->student?->industry;
        } elseif ($user->isIndustri() && $user->industry) {
            $industry = $user->industry;
            $teacher = $journals->first()?->student?->teacher ?? \App\Models\Teacher::first();
        } else {
            $teacher = $journals->first()?->student?->teacher ?? \App\Models\Teacher::first();
            $industry = $journals->first()?->student?->industry ?? \App\Models\Industry::first();
        }

        return view('exports.journals-pdf', compact('journals', 'filterStatus', 'teacher', 'industry'));
    }
}
