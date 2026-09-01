-- ==============================================================================
-- SISTEM INFORMASI & MONITORING PKL SMK NEGERI 10 MAKASSAR
-- PostgreSQL / Supabase Schema & Seed Data Dump
-- ==============================================================================

-- 1. Enable UUID Extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- 2. Drop Existing Tables (if any)
DROP TABLE IF EXISTS pkl_evaluations CASCADE;
DROP TABLE IF EXISTS pkl_monitorings CASCADE;
DROP TABLE IF EXISTS journals CASCADE;
DROP TABLE IF EXISTS attendances CASCADE;
DROP TABLE IF EXISTS students CASCADE;
DROP TABLE IF EXISTS industries CASCADE;
DROP TABLE IF EXISTS teachers CASCADE;
DROP TABLE IF EXISTS majors CASCADE;
DROP TABLE IF EXISTS attendance_settings CASCADE;
DROP TABLE IF EXISTS sessions CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS cache_locks CASCADE;
DROP TABLE IF EXISTS cache CASCADE;
DROP TABLE IF EXISTS failed_jobs CASCADE;
DROP TABLE IF EXISTS job_batches CASCADE;
DROP TABLE IF EXISTS jobs CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- -----------------------------------------------------------------------------
-- Table: users
-- -----------------------------------------------------------------------------
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP WITH TIME ZONE NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'siswa',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: password_reset_tokens
-- -----------------------------------------------------------------------------
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE NULL
);

-- -----------------------------------------------------------------------------
-- Table: sessions
-- -----------------------------------------------------------------------------
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id UUID NULL REFERENCES users(id) ON DELETE CASCADE,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX sessions_user_id_index ON sessions (user_id);
CREATE INDEX sessions_last_activity_index ON sessions (last_activity);

-- -----------------------------------------------------------------------------
-- Table: cache & cache_locks
-- -----------------------------------------------------------------------------
CREATE TABLE cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- -----------------------------------------------------------------------------
-- Table: jobs, job_batches, failed_jobs
-- -----------------------------------------------------------------------------
CREATE TABLE jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload TEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER NULL,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX jobs_queue_index ON jobs (queue);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INTEGER NOT NULL,
    pending_jobs INTEGER NOT NULL,
    failed_jobs INTEGER NOT NULL,
    failed_job_ids TEXT NOT NULL,
    options TEXT NULL,
    cancelled_at INTEGER NULL,
    created_at INTEGER NOT NULL,
    finished_at INTEGER NULL
);

CREATE TABLE failed_jobs (
    id BIGSERIAL PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: attendance_settings
-- -----------------------------------------------------------------------------
CREATE TABLE attendance_settings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    check_in_start TIME NOT NULL DEFAULT '06:00:00',
    check_in_late_time TIME NOT NULL DEFAULT '08:00:00',
    check_out_time TIME NOT NULL DEFAULT '16:00:00',
    check_out_early_time TIME NOT NULL DEFAULT '15:30:00',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: majors (Jurusan Kejuruan)
-- -----------------------------------------------------------------------------
CREATE TABLE majors (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name VARCHAR(255) UNIQUE NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: teachers (Guru Pembimbing)
-- -----------------------------------------------------------------------------
CREATE TABLE teachers (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID UNIQUE NULL REFERENCES users(id) ON DELETE SET NULL,
    nip VARCHAR(50) UNIQUE NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: industries (Mitra Industri / DUDI)
-- -----------------------------------------------------------------------------
CREATE TABLE industries (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID UNIQUE NULL REFERENCES users(id) ON DELETE SET NULL,
    name VARCHAR(255) NOT NULL,
    address TEXT NULL,
    contact_person VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: students (Siswa PKL)
-- -----------------------------------------------------------------------------
CREATE TABLE students (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    nisn VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    class_name VARCHAR(50) NOT NULL,
    major_id UUID NULL REFERENCES majors(id) ON DELETE SET NULL,
    teacher_id UUID NULL REFERENCES teachers(id) ON DELETE SET NULL,
    industry_id UUID NULL REFERENCES industries(id) ON DELETE SET NULL,
    phone VARCHAR(50) NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: attendances (Presensi Kehadiran)
-- -----------------------------------------------------------------------------
CREATE TABLE attendances (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    check_in_time TIME NULL,
    check_in_status VARCHAR(50) NULL,
    check_in_notes TEXT NULL,
    check_in_photo TEXT NULL,
    check_out_time TIME NULL,
    check_out_status VARCHAR(50) NULL,
    check_out_notes TEXT NULL,
    check_out_photo TEXT NULL,
    location TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_student_date UNIQUE (student_id, date)
);

-- -----------------------------------------------------------------------------
-- Table: journals (Jurnal Kegiatan Harian)
-- -----------------------------------------------------------------------------
CREATE TABLE journals (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    activity_title VARCHAR(255) NOT NULL,
    activity_description TEXT NOT NULL,
    photo TEXT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    verification_notes TEXT NULL,
    verified_at TIMESTAMP WITH TIME ZONE NULL,
    verified_by UUID NULL REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: pkl_monitorings (Supervisi Pembimbing)
-- -----------------------------------------------------------------------------
CREATE TABLE pkl_monitorings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    teacher_id UUID NOT NULL REFERENCES teachers(id) ON DELETE CASCADE,
    industry_id UUID NOT NULL REFERENCES industries(id) ON DELETE CASCADE,
    student_id UUID NULL REFERENCES students(id) ON DELETE SET NULL,
    visit_date DATE NOT NULL,
    notes TEXT NOT NULL,
    obstacles TEXT NULL,
    recommendations TEXT NULL,
    photo TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------------------------------
-- Table: pkl_evaluations (Penilaian PKL)
-- -----------------------------------------------------------------------------
CREATE TABLE pkl_evaluations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID UNIQUE NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    industry_id UUID NOT NULL REFERENCES industries(id) ON DELETE CASCADE,
    evaluator_user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    aspect_attitude NUMERIC(5, 2) NOT NULL DEFAULT 0,
    aspect_technical NUMERIC(5, 2) NOT NULL DEFAULT 0,
    aspect_managerial NUMERIC(5, 2) NOT NULL DEFAULT 0,
    aspect_report NUMERIC(5, 2) NOT NULL DEFAULT 0,
    aspect_presentation NUMERIC(5, 2) NOT NULL DEFAULT 0,
    final_score NUMERIC(5, 2) NOT NULL DEFAULT 0,
    predicate VARCHAR(10) NOT NULL DEFAULT 'B',
    notes TEXT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ==============================================================================
-- INITIAL SEED DATA
-- Default Password for all demo accounts: password123
-- ==============================================================================

-- 1. Attendance Settings
INSERT INTO attendance_settings (id, check_in_start, check_in_late_time, check_out_time, check_out_early_time, created_at, updated_at)
VALUES ('a1b2c3d4-e5f6-7890-abcd-111111111111', '06:00:00', '08:00:00', '16:00:00', '15:30:00', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 2. Users (Password: password123)
INSERT INTO users (id, name, username, email, password, role, created_at, updated_at) VALUES
('b1111111-1111-1111-1111-111111111111', 'Admin SMKN 10 Makassar', 'admin', 'admin@smkn10makassar.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'admin', NOW(), NOW()),
('b2222222-2222-2222-2222-222222222222', 'Drs. Budi Santoso, M.Pd.', 'guru_budi', 'budi@smkn10makassar.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'guru', NOW(), NOW()),
('b3333333-3333-3333-3333-333333333333', 'PT Telkom Indonesia (Makassar)', 'pt_telkom', 'hrd@telkom-makassar.co.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'industri', NOW(), NOW()),
('b4444444-4444-4444-4444-444444444444', 'Andi Pratama', 'siswa_andi', 'andi@siswa.smkn10.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'siswa', NOW(), NOW()),
('b5555555-5555-5555-5555-555555555555', 'Siti Nurhaliza', 'siswa_siti', 'siti@siswa.smkn10.sch.id', '$2y$12$R7JdJznQnRokWg1dvGX7yeg4yLtMR8i2R2Cof3.QrXvXZL1HR05ri', 'siswa', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 3. Majors (Jurusan)
INSERT INTO majors (id, name, code, created_at, updated_at) VALUES
('d1111111-1111-1111-1111-111111111111', 'Rekayasa Perangkat Lunak', 'RPL', NOW(), NOW()),
('d2222222-2222-2222-2222-222222222222', 'Teknik Komputer & Jaringan', 'TKJ', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 4. Teachers (Guru Pembimbing)
INSERT INTO teachers (id, user_id, nip, name, phone, created_at, updated_at) VALUES
('c2222222-2222-2222-2222-222222222222', 'b2222222-2222-2222-2222-222222222222', '197508122000031001', 'Drs. Budi Santoso, M.Pd.', '081234567890', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 5. Industries (Mitra Industri)
INSERT INTO industries (id, user_id, name, address, contact_person, phone, created_at, updated_at) VALUES
('c3333333-3333-3333-3333-333333333333', 'b3333333-3333-3333-3333-333333333333', 'PT Telkom Indonesia Witel Makassar', 'Jl. AP Pettarani No. 2, Makassar', 'Rahmat Hidayat, S.T.', '085299887766', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 6. Students (Siswa PKL)
INSERT INTO students (id, user_id, nisn, name, class_name, major_id, teacher_id, industry_id, phone, created_at, updated_at) VALUES
('c4444444-4444-4444-4444-444444444444', 'b4444444-4444-4444-4444-444444444444', '0061234567', 'Andi Pratama', 'XII RPL 1', 'd1111111-1111-1111-1111-111111111111', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', '081344556677', NOW(), NOW()),
('c5555555-5555-5555-5555-555555555555', 'b5555555-5555-5555-5555-555555555555', '0067654321', 'Siti Nurhaliza', 'XII TKJ 2', 'd2222222-2222-2222-2222-222222222222', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', '081399001122', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 7. Sample Attendance Record
INSERT INTO attendances (id, student_id, date, check_in_time, check_in_status, check_in_notes, check_out_time, check_out_status, check_out_notes, location, created_at, updated_at) VALUES
('e1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', CURRENT_DATE, '07:45:00', 'Tepat Waktu', 'Tiba di kantor mitra tepat waktu', '16:05:00', 'Tepat Waktu', 'Pekerjaan magang hari ini selesai', '-5.147665, 119.432731', NOW(), NOW())
ON CONFLICT (student_id, date) DO NOTHING;

-- 8. Sample Journal Record
INSERT INTO journals (id, student_id, date, activity_title, activity_description, status, verification_notes, verified_at, verified_by, created_at, updated_at) VALUES
('f1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', CURRENT_DATE, 'Implementasi Modul Otentikasi dan Dashboard', 'Mengembangkan antarmuka monitoring presensi dan integrasi API kamera WebRTC bersama tim IT industri.', 'approved', 'Pekerjaan sangat baik dan sesuai target.', NOW(), 'b3333333-3333-3333-3333-333333333333', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 9. Sample Monitoring Record
INSERT INTO pkl_monitorings (id, teacher_id, industry_id, student_id, visit_date, notes, obstacles, recommendations, created_at, updated_at) VALUES
('g1111111-1111-1111-1111-111111111111', 'c2222222-2222-2222-2222-222222222222', 'c3333333-3333-3333-3333-333333333333', 'c4444444-4444-4444-4444-444444444444', CURRENT_DATE, 'Monitoring berkala dan koordinasi dengan instruktur industri mengenai perkembangan kompetensi siswa.', 'Tidak ada kendala berarti', 'Pertahankan performa dan kedisiplinan kerja siswa.', NOW(), NOW())
ON CONFLICT (id) DO NOTHING;

-- 10. Sample Evaluation Record (Full Score F1-F6)
INSERT INTO pkl_evaluations (id, student_id, industry_id, evaluator_user_id, aspect_attitude, aspect_technical, aspect_managerial, aspect_report, aspect_presentation, final_score, predicate, notes, created_at, updated_at) VALUES
('h1111111-1111-1111-1111-111111111111', 'c4444444-4444-4444-4444-444444444444', 'c3333333-3333-3333-3333-333333333333', 'b3333333-3333-3333-3333-333333333333', 95.00, 92.00, 90.00, 94.00, 96.00, 93.40, 'A', 'Siswa memiliki etos kerja dan kemampuan teknis yang luar biasa.', NOW(), NOW())
ON CONFLICT (student_id) DO NOTHING;
