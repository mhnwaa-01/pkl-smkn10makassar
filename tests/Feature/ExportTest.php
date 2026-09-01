<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Industry;
use App\Models\Journal;
use App\Models\Major;
use App\Models\PklEvaluation;
use App\Models\PklMonitoring;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $studentUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->studentUser = User::where('role', 'siswa')->first();
    }

    public function test_attendance_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('attendance.export.excel'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xls'));
    }

    public function test_attendance_export_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('attendance.export.pdf'));
        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI PRESENSI KEHADIRAN SISWA PKL');
    }

    public function test_journals_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('journals.export.excel'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xls'));
    }

    public function test_journals_export_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('journals.export.pdf'));
        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI JURNAL KEGIATAN HARIAN SISWA PKL');
    }

    public function test_monitoring_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('monitoring.export.excel'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xls'));
    }

    public function test_monitoring_export_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('monitoring.export.pdf'));
        $response->assertStatus(200);
        $response->assertSee('LEMBAR CATATAN KUNJUNGAN');
    }

    public function test_evaluations_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('evaluations.export.excel'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), '.xls'));
    }

    public function test_evaluations_export_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('evaluations.export.pdf'));
        $response->assertStatus(200);
        $response->assertSee('REKAPITULASI PENILAIAN PRAKTIK KERJA LAPANGAN (PKL)');
    }

    public function test_student_cannot_access_export_routes(): void
    {
        $responseAttendance = $this->actingAs($this->studentUser)->get(route('attendance.export.pdf'));
        $responseAttendance->assertStatus(403);

        $responseJournal = $this->actingAs($this->studentUser)->get(route('journals.export.pdf'));
        $responseJournal->assertStatus(403);

        $responseEvaluation = $this->actingAs($this->studentUser)->get(route('evaluations.export.pdf'));
        $responseEvaluation->assertStatus(403);
    }

    public function test_apk_download_route(): void
    {
        $response = $this->get(route('download.apk'));
        $response->assertStatus(200);
        $this->assertEquals('application/vnd.android.package-archive', $response->headers->get('content-type'));
    }
}
