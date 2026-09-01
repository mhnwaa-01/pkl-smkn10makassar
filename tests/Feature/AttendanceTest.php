<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    public function test_check_in_saves_photo_and_location(): void
    {
        $siswaUser = User::where('role', 'siswa')->first();
        $student = $siswaUser->student;

        $file = UploadedFile::fake()->image('face_in.jpg');

        $response = $this->actingAs($siswaUser)->post('/attendance/check-in', [
            'photo' => $file,
            'location' => '-5.147600, 119.432700',
            'notes' => 'Tiba di kantor tepat waktu',
        ]);

        $response->assertRedirect('/journals');

        $attendance = Attendance::where('student_id', $student->id)->where('date', date('Y-m-d'))->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_photo);
        $this->assertEquals('-5.147600, 119.432700', $attendance->location);
        Storage::disk('public')->assertExists($attendance->check_in_photo);
    }

    public function test_check_out_saves_photo_and_calculates_duration(): void
    {
        $siswaUser = User::where('role', 'siswa')->first();
        $student = $siswaUser->student;

        // Set allowed check out time to early morning so lock doesn't trigger
        $setting = AttendanceSetting::getSetting();
        $setting->update([
            'check_out_early_time' => '00:00:00',
            'check_out_time' => '00:01:00',
        ]);

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => date('Y-m-d'),
            'check_in_time' => '08:00:00',
            'check_in_status' => 'Tepat Waktu',
            'location' => '-5.147600, 119.432700',
        ]);

        $file = UploadedFile::fake()->image('face_out.jpg');

        $response = $this->actingAs($siswaUser)->post('/attendance/check-out', [
            'photo' => $file,
            'location' => '-5.147600, 119.432700',
            'notes' => 'Selesai tugas hari ini',
        ]);

        $response->assertRedirect('/attendance');

        $attendance->refresh();
        $this->assertNotNull($attendance->check_out_photo);
        Storage::disk('public')->assertExists($attendance->check_out_photo);
        $this->assertNotEquals('-', $attendance->work_duration);
    }

    public function test_check_out_is_locked_when_before_allowed_time(): void
    {
        $siswaUser = User::where('role', 'siswa')->first();
        $student = $siswaUser->student;

        // Set allowed check out time in the future
        $setting = AttendanceSetting::getSetting();
        $setting->update([
            'check_out_early_time' => '23:59:00',
            'check_out_time' => '23:59:59',
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'date' => date('Y-m-d'),
            'check_in_time' => '08:00:00',
            'check_in_status' => 'Tepat Waktu',
        ]);

        $file = UploadedFile::fake()->image('face_out.jpg');

        $response = $this->actingAs($siswaUser)->post('/attendance/check-out', [
            'photo' => $file,
        ]);

        $response->assertSessionHas('error');
    }
}
