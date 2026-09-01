<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laporan') - SMKN 10 Makassar PKL</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #0f172a;
            background: #ffffff;
            font-size: 10pt;
            line-height: 1.4;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        /* Official Kop Surat Header */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
        }
        .kop-logo-box {
            width: 105px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-shrink: 0;
        }
        .kop-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 10px;
        }
        .kop-text h3 {
            font-size: 11.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            line-height: 1.25;
            margin: 0;
        }
        .kop-text h2 {
            font-size: 16.5pt;
            font-weight: 900;
            text-transform: uppercase;
            color: #1e3a8a;
            letter-spacing: 1.5px;
            line-height: 1.2;
            margin: 2px 0 4px 0;
        }
        .kop-text p {
            font-size: 8.5pt;
            color: #334155;
            line-height: 1.35;
            margin: 0;
        }
        .kop-spacer {
            width: 105px;
            flex-shrink: 0;
        }

        /* Official Government Double Line */
        .kop-line-thick {
            border-bottom: 3px solid #0f172a;
            margin-bottom: 2px;
        }
        .kop-line-thin {
            border-bottom: 1px solid #0f172a;
            margin-bottom: 22px;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 15px 0 20px 0;
        }
        .doc-title h1 {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }
        .doc-title p {
            font-size: 9.5pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* Data Tables */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 8.5pt;
        }
        table.report-table th, 
        table.report-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }
        table.report-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
        }
        table.report-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }

        /* Signature Section */
        .signature-section {
            margin-top: 35px;
            page-break-inside: avoid;
            width: 100%;
        }
        .signature-heading {
            text-align: center;
            margin-bottom: 25px;
        }
        .signature-heading p {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .signature-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            padding: 0 40px;
        }
        .signature-box {
            text-align: center;
            width: 280px;
            font-size: 9.5pt;
        }
        .signature-space {
            height: 65px;
        }
        .signature-box .name {
            font-weight: 700;
            text-decoration: underline;
        }
        .signature-box .nip {
            font-size: 8.5pt;
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
        }
        .signature-box .subtext {
            font-size: 8pt;
            color: #64748b;
        }

        /* Footer */
        .doc-footer {
            margin-top: 45px;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #64748b;
            page-break-inside: avoid;
        }

        /* Print Controls (Hidden when printing) */
        .no-print-bar {
            background: #0f172a;
            color: white;
            padding: 12px 20px;
            border-radius: 9999px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 9pt;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }
        .btn-back {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 9pt;
            font-weight: 600;
        }
        .btn-back:hover {
            color: white;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                padding: 0;
                background: white;
            }
            table.report-table th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Floating Print Bar -->
        <div class="no-print-bar">
            <div>
                <strong style="font-size: 10pt;">Dokumen Cetak / PDF Resmi</strong>
                <span style="font-size: 8.5pt; opacity: 0.8; display: block;">SMKN 10 Makassar — Sistem Informasi PKL Terpadu</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="javascript:window.history.back()" class="btn-back">&larr; Kembali</a>
                <button onclick="window.print()" class="btn-print">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- Official Letterhead (KOP SURAT) -->
        <div class="kop-surat">
            <div class="kop-logo-box">
                <img src="{{ asset('logo-sekolah.png') }}" class="kop-logo" alt="Logo SMKN 10">
            </div>
            <div class="kop-text">
                <h3>PEMERINTAH PROVINSI SULAWESI SELATAN</h3>
                <h3>DINAS PENDIDIKAN</h3>
                <h2>SMK NEGERI 10 MAKASSAR</h2>
                <p>Jl. Bontomanai No. 14, Gn. Sari, Kec. Rappocini, Kota Makassar, Sulawesi Selatan 90222</p>
                <p>Laman: smkn10makassar.sch.id • Pos-el: info@smkn10makassar.sch.id • Kode Pos: 90222</p>
            </div>
            <div class="kop-spacer"></div>
        </div>
        <div class="kop-line-thick"></div>
        <div class="kop-line-thin"></div>

        <!-- Document Content -->
        @yield('content')

        <!-- Signature Section -->
        @yield('signatures')

        <!-- Official Footer with Print Timestamp -->
        <footer class="doc-footer">
            <span>Dicetak pada: @formatdate(now()) WITA</span>
            <span>SMK Negeri 10 Makassar • Portal Praktik Kerja Lapangan</span>
        </footer>
    </div>

</body>
</html>
