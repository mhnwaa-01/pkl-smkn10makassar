@extends('exports.layout')

@section('title', 'Laporan Jurnal Harian PKL')

@section('content')
<div class="doc-title">
    <h1>REKAPITULASI JURNAL KEGIATAN HARIAN SISWA PKL</h1>
    <p>Status Verifikasi: {{ $filterStatus ? ucfirst($filterStatus) : 'Semua Status' }}</p>
</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width: 30px; text-align: center;">No</th>
            <th style="width: 75px;">Tanggal</th>
            <th>Nama Siswa & Kelas</th>
            <th>Industri Mitra</th>
            <th>Judul & Deskripsi Kegiatan</th>
            <th style="width: 90px; text-align: center;">Dokumentasi Foto</th>
            <th style="width: 70px; text-align: center;">Status</th>
            <th>Catatan Pembimbing</th>
        </tr>
    </thead>
    <tbody>
        @forelse($journals as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="white-space: nowrap;">@formatdate($item->date)</td>
                <td>
                    <strong>{{ $item->student->name ?? '-' }}</strong><br>
                    <span style="font-size: 8pt; color: #64748b;">{{ $item->student->class_name ?? '-' }} ({{ $item->student->major->code ?? '-' }})</span>
                </td>
                <td>{{ $item->student->industry->name ?? '-' }}</td>
                <td>
                    <strong style="color: #1e3a8a;">{{ $item->activity_title }}</strong>
                    <p style="font-size: 8.5pt; color: #334155; margin-top: 3px; white-space: pre-line;">{{ $item->activity_description }}</p>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    @if($item->photo)
                        <div style="width: 80px; height: 60px; margin: 0 auto; border-radius: 6px; overflow: hidden; border: 1px solid #cbd5e1; background: #0f172a;">
                            <img src="{{ asset('storage/' . $item->photo) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Foto Dokumentasi">
                        </div>
                    @else
                        <span style="font-size: 7.5pt; color: #94a3b8; font-style: italic;">Tanpa Foto</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    @if($item->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif($item->status === 'rejected')
                        <span class="badge badge-danger">Rejected</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
                <td style="font-size: 8.5pt; font-style: italic; color: #475569;">
                    {{ $item->verification_notes ?: '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #64748b;">Tidak ada data jurnal kegiatan yang sesuai.</td>
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
            <p class="subtext">{{ $industry->name ?? ($journals->first()?->student?->industry->name ?? 'Mitra Industri PKL') }}</p>
            <div class="signature-space"></div>
            <p class="name">{{ $industry->contact_person ?? '( .................................................... )' }}</p>
            <p class="subtext">Instruktur / Pembimbing Lapangan</p>
        </div>

        <!-- Kotak Sebelah Kanan: Guru Pembimbing PKL -->
        <div class="signature-box">
            <p><strong>Guru Pembimbing PKL</strong></p>
            <p class="subtext">SMK Negeri 10 Makassar</p>
            <div class="signature-space"></div>
            <p class="name">{{ $teacher->name ?? ($journals->first()?->student?->teacher->name ?? '( .................................................... )') }}</p>
            <p class="nip">NIP. {{ $teacher->nip ?? ($journals->first()?->student?->teacher->nip ?? '.............................................') }}</p>
        </div>
    </div>
</div>
@endsection
