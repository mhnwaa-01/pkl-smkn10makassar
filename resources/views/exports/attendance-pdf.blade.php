@extends('exports.layout')

@section('title', 'Laporan Rekapitulasi Presensi PKL')

@section('content')
<div class="doc-title">
    <h1>REKAPITULASI PRESENSI KEHADIRAN SISWA PKL</h1>
    <p>Periode Tanggal: {{ $filterDate ? \Carbon\Carbon::parse($filterDate)->setTimezone('Asia/Makassar')->locale('id')->translatedFormat('l, d F Y') : 'Seluruh Riwayat Presensi' }}</p>
</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width: 30px; text-align: center;">No</th>
            <th style="width: 75px;">Tanggal</th>
            <th>Nama Siswa & NISN</th>
            <th>Kelas & Jurusan</th>
            <th>Industri Tempat PKL</th>
            <th>Jam Masuk</th>
            <th>Status Masuk</th>
            <th>Jam Pulang</th>
            <th>Status Pulang</th>
            <th>Lama Kerja</th>
        </tr>
    </thead>
    <tbody>
        @forelse($attendances as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="white-space: nowrap;">@formatdate($item->date)</td>
                <td>
                    <strong>{{ $item->student->name ?? '-' }}</strong><br>
                    <span style="font-size: 8pt; color: #64748b;">NISN: {{ $item->student->nisn ?? '-' }}</span>
                </td>
                <td>{{ $item->student->class_name ?? '-' }} ({{ $item->student->major->code ?? '-' }})</td>
                <td>{{ $item->student->industry->name ?? '-' }}</td>
                <td style="font-family: monospace;">{{ $item->check_in_time ? $item->check_in_time . ' WITA' : '-' }}</td>
                <td>
                    @if($item->check_in_status === 'Tepat Waktu')
                        <span class="badge badge-success">Tepat Waktu</span>
                    @elseif($item->check_in_status === 'Terlambat')
                        <span class="badge badge-danger">Terlambat</span>
                    @else
                        -
                    @endif
                </td>
                <td style="font-family: monospace;">{{ $item->check_out_time ? $item->check_out_time . ' WITA' : '-' }}</td>
                <td>
                    @if($item->check_out_status === 'Tepat Waktu')
                        <span class="badge badge-success">Tepat Waktu</span>
                    @elseif($item->check_out_status === 'Pulang Cepat')
                        <span class="badge badge-warning">Pulang Cepat</span>
                    @else
                        -
                    @endif
                </td>
                <td style="font-family: monospace;">{{ $item->work_duration ?: '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data presensi yang sesuai.</td>
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
            <p class="subtext">{{ $industry->name ?? ($attendances->first()?->student?->industry->name ?? 'Mitra Industri PKL') }}</p>
            <div class="signature-space"></div>
            <p class="name">{{ $industry->contact_person ?? '( .................................................... )' }}</p>
            <p class="subtext">Pimpinan / Instruktur Lapangan</p>
        </div>

        <!-- Kotak Sebelah Kanan: Guru Pembimbing PKL -->
        <div class="signature-box">
            <p><strong>Guru Pembimbing PKL</strong></p>
            <p class="subtext">SMK Negeri 10 Makassar</p>
            <div class="signature-space"></div>
            <p class="name">{{ $teacher->name ?? ($attendances->first()?->student?->teacher->name ?? '( .................................................... )') }}</p>
            <p class="nip">NIP. {{ $teacher->nip ?? ($attendances->first()?->student?->teacher->nip ?? '.............................................') }}</p>
        </div>
    </div>
</div>
@endsection
