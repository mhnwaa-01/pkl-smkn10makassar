@extends('exports.layout')

@section('title', 'Laporan Rekapitulasi Nilai PKL')

@section('content')
<div class="doc-title">
    <h1>REKAPITULASI PENILAIAN PRAKTIK KERJA LAPANGAN (PKL)</h1>
    <p>SMK Negeri 10 Makassar • Tahun Ajaran 2026/2027</p>
</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width: 25px; text-align: center;">No</th>
            <th>Nama Siswa</th>
            <th>NISN</th>
            <th>Kelas / Jurusan</th>
            <th>Industri Tempat PKL</th>
            <th style="text-align: center; width: 45px;">F1 (Sikap)</th>
            <th style="text-align: center; width: 45px;">F2&3 (Teknis)</th>
            <th style="text-align: center; width: 45px;">F4 (Wirausaha)</th>
            <th style="text-align: center; width: 45px;">F5 (Laporan)</th>
            <th style="text-align: center; width: 45px;">F6 (Presentasi)</th>
            <th style="text-align: center; width: 50px;">Nilai Akhir</th>
            <th style="text-align: center; width: 65px;">Predikat</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $st)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $st->name }}</strong></td>
                <td style="font-family: monospace;">{{ $st->nisn }}</td>
                <td>{{ $st->class_name }} ({{ $st->major->code ?? '-' }})</td>
                <td>{{ $st->industry->name ?? '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ ($st->evaluation && $st->evaluation->aspect_attitude > 0) ? $st->evaluation->aspect_attitude : '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ ($st->evaluation && $st->evaluation->aspect_technical > 0) ? $st->evaluation->aspect_technical : '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ ($st->evaluation && $st->evaluation->aspect_managerial > 0) ? $st->evaluation->aspect_managerial : '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ ($st->evaluation && $st->evaluation->aspect_report > 0) ? $st->evaluation->aspect_report : '-' }}</td>
                <td style="text-align: center; font-family: monospace;">{{ ($st->evaluation && $st->evaluation->aspect_presentation > 0) ? $st->evaluation->aspect_presentation : '-' }}</td>
                <td style="text-align: center; font-family: monospace; font-weight: bold; color: #1e3a8a;">
                    @if($st->evaluation && $st->evaluation->aspect_attitude > 0 && $st->evaluation->aspect_technical > 0 && $st->evaluation->aspect_managerial > 0 && $st->evaluation->aspect_report > 0 && $st->evaluation->aspect_presentation > 0)
                        {{ $st->evaluation->final_score }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($st->evaluation && $st->evaluation->aspect_attitude > 0 && $st->evaluation->aspect_technical > 0 && $st->evaluation->aspect_managerial > 0 && $st->evaluation->aspect_report > 0 && $st->evaluation->aspect_presentation > 0)
                        @if($st->evaluation->predicate === 'A')
                            <span class="badge badge-success">A (Sangat Baik)</span>
                        @elseif($st->evaluation->predicate === 'B')
                            <span class="badge badge-info">B (Baik)</span>
                        @elseif($st->evaluation->predicate === 'C')
                            <span class="badge badge-warning">C (Cukup)</span>
                        @else
                            <span class="badge badge-danger">D (Kurang)</span>
                        @endif
                    @else
                        <span style="font-size: 7.5pt; color: #94a3b8;">Belum Lengkap</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 20px; color: #64748b;">Belum ada data nilai siswa PKL.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection

@section('signatures')
<div class="signature-section">
    <div class="signature-heading">
        <p>MENGETAHUI,</p>
    </div>

    <div class="signature-grid">
        <!-- Kotak Sebelah Kiri: Pembimbing Industri -->
        <div class="signature-box">
            <p><strong>Pembimbing Industri / DUDI</strong></p>
            <p class="subtext">{{ $industry->name ?? ($students->first()?->industry->name ?? 'Mitra Industri PKL') }}</p>
            <div class="signature-space"></div>
            <p class="name">{{ $industry->contact_person ?? '( .................................................... )' }}</p>
            <p class="subtext">Penilai Aspek Teknis & Sikap</p>
        </div>

        <!-- Kotak Sebelah Kanan: Guru Pembimbing PKL -->
        <div class="signature-box">
            <p><strong>Guru Pembimbing PKL</strong></p>
            <p class="subtext">SMK Negeri 10 Makassar</p>
            <div class="signature-space"></div>
            <p class="name">{{ $teacher->name ?? ($students->first()?->teacher->name ?? '( .................................................... )') }}</p>
            <p class="nip">NIP. {{ $teacher->nip ?? ($students->first()?->teacher->nip ?? '.............................................') }}</p>
        </div>
    </div>
</div>
@endsection
