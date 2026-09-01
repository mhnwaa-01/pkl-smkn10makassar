<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create majors table
        Schema::create('majors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        // 2. Insert default majors
        $defaultMajors = [
            [
                'id' => 'd1111111-1111-1111-1111-111111111111',
                'name' => 'Rekayasa Perangkat Lunak',
                'code' => 'RPL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'd2222222-2222-2222-2222-222222222222',
                'name' => 'Teknik Komputer & Jaringan',
                'code' => 'TKJ',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        DB::table('majors')->insert($defaultMajors);

        // 3. Add major_id to students table
        Schema::table('students', function (Blueprint $table) {
            $table->uuid('major_id')->nullable()->after('major');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('set null');
        });

        // 4. Migrate existing data
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            // Find major with matching name
            $major = DB::table('majors')->where('name', $student->major)->first();
            if ($major) {
                DB::table('students')->where('id', $student->id)->update(['major_id' => $major->id]);
            } else if (!empty($student->major)) {
                // If there's a custom major, let's create it first
                $newMajorId = (string) Str::uuid();
                $code = strtoupper(substr(str_replace(' ', '', $student->major), 0, 5));
                
                // Ensure unique code
                $baseCode = $code;
                $i = 1;
                while (DB::table('majors')->where('code', $code)->exists()) {
                    $code = $baseCode . $i;
                    $i++;
                }

                DB::table('majors')->insert([
                    'id' => $newMajorId,
                    'name' => $student->major,
                    'code' => $code,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('students')->where('id', $student->id)->update(['major_id' => $newMajorId]);
            }
        }

        // 5. Drop the old major column
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('major');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back major column
        Schema::table('students', function (Blueprint $table) {
            $table->string('major')->nullable()->after('class_name');
        });

        // Migrate back the major name
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            if ($student->major_id) {
                $major = DB::table('majors')->where('id', $student->major_id)->first();
                if ($major) {
                    DB::table('students')->where('id', $student->id)->update(['major' => $major->name]);
                }
            }
        }

        // Drop major_id and foreign key
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->dropColumn('major_id');
        });

        // Drop majors table
        Schema::dropIfExists('majors');
    }
};
