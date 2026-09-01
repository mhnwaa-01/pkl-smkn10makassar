<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Industries (Mitra Industri)
        Schema::create('industries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_person');
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 2. Teachers (Guru Pembimbing)
        Schema::create('teachers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->unique();
            $table->string('nip')->nullable()->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 3. Students (Siswa PKL)
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->string('class_name');
            $table->string('major');
            $table->uuid('teacher_id')->nullable();
            $table->uuid('industry_id')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('set null');
        });

        // 4. Attendances (Presensi Datang & Pulang)
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->enum('check_in_status', ['Tepat Waktu', 'Terlambat'])->nullable();
            $table->text('check_in_notes')->nullable();
            $table->text('check_in_photo')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('check_out_status', ['Tepat Waktu', 'Pulang Cepat'])->nullable();
            $table->text('check_out_notes')->nullable();
            $table->text('location')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unique(['student_id', 'date']);
        });

        // 5. Journals (Jurnal Harian Siswa)
        Schema::create('journals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->date('date');
            $table->string('activity_title');
            $table->text('activity_description');
            $table->text('photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });

        // 6. PKL Monitorings (Kunjungan Guru)
        Schema::create('pkl_monitorings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('teacher_id');
            $table->uuid('industry_id');
            $table->uuid('student_id')->nullable();
            $table->date('visit_date');
            $table->text('notes');
            $table->text('obstacles')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('photo')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
        });

        // 7. PKL Evaluations (Penilaian PKL)
        Schema::create('pkl_evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id')->unique();
            $table->uuid('industry_id');
            $table->uuid('evaluator_user_id');
            $table->decimal('aspect_attitude', 5, 2)->default(0);
            $table->decimal('aspect_technical', 5, 2)->default(0);
            $table->decimal('aspect_managerial', 5, 2)->default(0);
            $table->decimal('final_score', 5, 2)->default(0);
            $table->string('predicate', 5)->default('B');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
            $table->foreign('evaluator_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 8. Attendance Settings (Pengaturan Jam Masuk & Pulang)
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->time('check_in_start')->default('06:00:00');
            $table->time('check_in_late_time')->default('08:00:00');
            $table->time('check_out_time')->default('16:00:00');
            $table->time('check_out_early_time')->default('15:30:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
        Schema::dropIfExists('pkl_evaluations');
        Schema::dropIfExists('pkl_monitorings');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('industries');
    }
};
