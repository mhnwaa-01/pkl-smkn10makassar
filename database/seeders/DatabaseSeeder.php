<?php

namespace Database\Seeders;

use App\Models\AttendanceSetting;
use App\Models\Industry;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Settings
        AttendanceSetting::firstOrCreate(
            ['id' => 'a1b2c3d4-e5f6-7890-abcd-111111111111'],
            [
                'check_in_start' => '06:00:00',
                'check_in_late_time' => '08:00:00',
                'check_out_time' => '16:00:00',
                'check_out_early_time' => '15:30:00',
            ]
        );

        // 2. Admin User
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'id' => 'b1111111-1111-1111-1111-111111111111',
                'name' => 'Admin SMKN 10 Makassar',
                'email' => 'admin@smkn10makassar.sch.id',
                'password' => 'password123', // auto-hashed by model cast
                'role' => 'admin',
            ]
        );

        // 3. Guru User & Profile
        $userGuru = User::firstOrCreate(
            ['username' => 'guru_budi'],
            [
                'id' => 'b2222222-2222-2222-2222-222222222222',
                'name' => 'Drs. Budi Santoso, M.Pd.',
                'email' => 'budi@smkn10makassar.sch.id',
                'password' => 'password123',
                'role' => 'guru',
            ]
        );

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $userGuru->id],
            [
                'id' => 'c2222222-2222-2222-2222-222222222222',
                'nip' => '197508122000031001',
                'name' => 'Drs. Budi Santoso, M.Pd.',
                'phone' => '081234567890',
            ]
        );

        // 4. Industry User & Profile
        $userIndustri = User::firstOrCreate(
            ['username' => 'pt_telkom'],
            [
                'id' => 'b3333333-3333-3333-3333-333333333333',
                'name' => 'PT Telkom Indonesia (Makassar)',
                'email' => 'hrd@telkom-makassar.co.id',
                'password' => 'password123',
                'role' => 'industri',
            ]
        );

        $industry = Industry::firstOrCreate(
            ['user_id' => $userIndustri->id],
            [
                'id' => 'c3333333-3333-3333-3333-333333333333',
                'name' => 'PT Telkom Indonesia Witel Makassar',
                'address' => 'Jl. AP Pettarani No. 2, Makassar',
                'contact_person' => 'Rahmat Hidayat, S.T.',
                'phone' => '085299887766',
            ]
        );

        // 5. Seed Majors
        $rpl = \App\Models\Major::firstOrCreate(
            ['id' => 'd1111111-1111-1111-1111-111111111111'],
            ['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']
        );
        $tkj = \App\Models\Major::firstOrCreate(
            ['id' => 'd2222222-2222-2222-2222-222222222222'],
            ['name' => 'Teknik Komputer & Jaringan', 'code' => 'TKJ']
        );

        // 6. Siswa User 1 & Profile
        $userSiswa1 = User::firstOrCreate(
            ['username' => 'siswa_andi'],
            [
                'id' => 'b4444444-4444-4444-4444-444444444444',
                'name' => 'Andi Pratama',
                'email' => 'andi@siswa.smkn10.sch.id',
                'password' => 'password123',
                'role' => 'siswa',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userSiswa1->id],
            [
                'id' => 'c4444444-4444-4444-4444-444444444444',
                'nisn' => '0061234567',
                'name' => 'Andi Pratama',
                'class_name' => 'XII RPL 1',
                'major_id' => $rpl->id,
                'teacher_id' => $teacher->id,
                'industry_id' => $industry->id,
                'phone' => '081344556677',
            ]
        );

        // 7. Siswa User 2 & Profile
        $userSiswa2 = User::firstOrCreate(
            ['username' => 'siswa_siti'],
            [
                'id' => 'b5555555-5555-5555-5555-555555555555',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@siswa.smkn10.sch.id',
                'password' => 'password123',
                'role' => 'siswa',
            ]
        );

        Student::firstOrCreate(
            ['user_id' => $userSiswa2->id],
            [
                'id' => 'c5555555-5555-5555-5555-555555555555',
                'nisn' => '0067654321',
                'name' => 'Siti Nurhaliza',
                'class_name' => 'XII TKJ 2',
                'major_id' => $tkj->id,
                'teacher_id' => $teacher->id,
                'industry_id' => $industry->id,
                'phone' => '081399001122',
            ]
        );
    }
}
