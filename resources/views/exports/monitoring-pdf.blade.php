@extends('exports.layout')

@section('title', 'Laporan Monitoring & Bimbingan PKL')

@section('content')
<div class="doc-title">
    <h1>LEMBAR CATATAN KUNJUNGAN & MONITORING PKL</h1>
    <p>Dokumen Resmi Supervisi Bimbingan Lapangan</p>
</div>

<table class="report-table">
    <thead>
        <tr>
            <th style="width: 30px; text-align: center;">No</th>
            <th style="width: 75px;">Tanggal</th>
            <th>Industri Mitra</th>
            <th>Guru Pembimbing</th>
            <th>Catatan Bimbingan Lapangan</th>
            <th>Kendala Ditemukan</th>
            <th>Rekomendasi Tindak Lanjut</th>
        </tr>
    </thead>
    <tbody>
        @forelse($monitorings as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="white-space: nowrap;">@formatdate($item->visit_date)</td>
                <td>
                    <strong style="color: #1e3a8a;">{{ $item->industry->name ?? '-' }}</strong><br>
                    <span style="font-size: 8pt; color: #64748b;">PJ: {{ $item->industry->contact_person ?? '-' }}</span>
                </td>
                <td>
                    <strong>{{ $item->teacher->name ?? '-' }}</strong><br>
                    <span style="font-size: 8pt; color: #64748b;">NIP: {{ $item->teacher->nip ?? '-' }}</span>
                </td>
                <td style="font-size: 8.5pt; color: #1e293b;">{{ $item->notes }}</td>
                <td style="font-size: 8.5pt; color: #9a3412;">{{ $item->obstacles ?? 'Tidak ada' }}</td>
                <td style="font-size: 8.5pt; color: #166534;">{{ $item->recommendations ?? 'Tidak ada' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #64748b;">Belum ada catatan monitoring PKL yang tercatat.</td>
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
        <!-- Kotak Sebelah Kiri: Guru Pembimbing -->
        <div class="signature-box">
            <p><strong>Guru Pembimbing Lapangan</strong></p>
            <p class="subtext">SMK Negeri 10 Makassar</p>
            <div class="signature-space"></div>
            <p class="name">{{ $teacher->name ?? ($monitorings->first()?->teacher->name ?? '( .................................................... )') }}</p>
            <p class="nip">NIP. {{ $teacher->nip ?? ($monitorings->first()?->teacher->nip ?? '.............................................') }}</p>
        </div>

        <!-- Kotak Sebelah Kanan: Kepala Sekolah -->
        <div class="signature-box">
            <p><strong>Kepala SMK Negeri 10 Makassar</strong></p>
            <p class="subtext">Dinas Pendidikan Provinsi Sulsel</p>
            <div class="signature-space"></div>
            <p class="name">( .................................................... )</p>
            <p class="nip">NIP. .............................................</p>
        </div>
    </div>
</div>
@endsection
