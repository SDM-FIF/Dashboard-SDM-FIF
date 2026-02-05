<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Pengujian - {{ $calonDosen->nama }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 50px;
            height: auto;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-row {
            margin: 3px 0;
        }
        
        .info-label {
            display: inline-block;
            width: 180px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .section-header {
            background-color: #d9ead3 !important;
            font-weight: bold;
            padding: 8px !important;
        }
        
        .total-row {
            background-color: #cfe2f3 !important;
            font-weight: bold;
            text-align: center;
        }
        
        .catatan-box {
            border: 1px solid #000;
            background-color: #fef9e7;
            padding: 10px;
            min-height: 80px;
            margin-bottom: 15px;
        }
        
        .catatan-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .pengesahan {
            margin-top: 30px;
            text-align: right;
        }
        
        .pengesahan-content {
            display: inline-block;
            text-align: center;
            min-width: 250px;
        }
        
        .qrcode {
            margin: 15px 0;
        }
        
        .qrcode img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
    @foreach($penilaianList as $index => $data)
    <!-- Penilaian from Dosen {{ $index + 1 }} -->
    <div class="header">
        @if(file_exists($data['logoPath']))
        <?php
            $logoData = base64_encode(file_get_contents($data['logoPath']));
            $logoSrc = 'data:image/png;base64,' . $logoData;
        ?>
        <img src="{{ $logoSrc }}" class="logo" alt="Logo Telkom">
        @endif
        <h1>FORM PENILAIAN CALON DOSEN</h1>
        <h2>TELKOM UNIVERSITY</h2>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nama Calon Dosen</span>: {{ $data['calonDosen']->nama }}
        </div>
        <div class="info-row">
            <span class="info-label">Program Studi</span>: {{ $data['calonDosen']->prodi->nama_prodi ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Tahun Ajaran</span>: {{ $data['jadwal']->tahunAjar->label ?? '-' }}
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pengujian</span>: {{ \Carbon\Carbon::parse($data['jadwal']->tanggal_pengujian)->format('d F Y') }}
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 55%;">Kriteria Penilaian</th>
                <th style="width: 15%;">Nilai</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" class="section-header">A. KUALIFIKASI (40%)</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Jalur Lamaran / Pendidikan = {{ $data['calonDosen']->jalur_lamaran ?? '-' }}</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_jalur_lamaran, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jabatan Fungsional Akademik (JFA) = {{ $data['calonDosen']->jabatan_fungsional_akademik ?? 'NJFA' }}</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_jfa, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>3</td>
                <td>H-Index = {{ $data['calonDosen']->h_index ?? 0 }}</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_h_index, 2) }}</td>
                <td>Rata-rata A = {{ number_format($data['penilaian']->rata_a, 2) }}</td>
            </tr>
            
            <tr>
                <td colspan="4" class="section-header">B. MICRO TEACHING (20%)</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Penguasaan materi & audiens</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_pma, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Sistematika (kemudahan dipahami)</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_sistematika, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Kejelasan suara & tulisan</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kst, 2) }}</td>
                <td>Rata-rata B = {{ number_format($data['penilaian']->rata_b, 2) }}</td>
            </tr>
            
            <tr>
                <td colspan="4" class="section-header">C. WAWANCARA (40%)</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Motivasi</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_motivasi, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Kemampuan mengajar</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmp_mengajar, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Kemampuan mengembangkan kurikulum Pengajaran</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmp_mkp, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Kemampuan penelitian & publikasi</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmp_pp, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Kemampuan Abdimas</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmp_abdimas, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>6</td>
                <td>Kemampuan Bekerjasama dengan tim</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmp_bdt, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>7</td>
                <td>Keahlian lainnya</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_keahlian_lainnya, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td>8</td>
                <td>Komitmen waktu dan kesediaan melakukan hal diluar tugas pokok</td>
                <td style="text-align: center;">{{ number_format($data['penilaian']->nilai_kmt_wkm, 2) }}</td>
                <td>Rata-rata C = {{ number_format($data['penilaian']->rata_c, 2) }}</td>
            </tr>
            
            <tr>
                <td colspan="2" class="total-row">TOTAL NILAI (Rata-rata Berbobot)</td>
                <td class="total-row">{{ number_format($data['penilaian']->rata_nilai, 2) }}</td>
                <td class="total-row">{{ $data['penilaian']->keterangan_berbobot }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">Kesiapan bergabung segera?</td>
                <td colspan="2" style="text-align: center;">{{ $data['penilaian']->kesiapan ? 'YA' : 'TIDAK/PIKIR-PIKIR' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;">Bersedia dengan standard gaji?</td>
                <td colspan="2" style="text-align: center;">{{ $data['penilaian']->kesediaan ? 'YA' : 'TIDAK/PIKIR-PIKIR' }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="catatan-box">
        <div class="catatan-label">Catatan/Komentar:</div>
        <div>{{ $data['penilaian']->catatan_penilai ?? '' }}</div>
    </div>
    
    <div class="pengesahan">
        <div class="pengesahan-content">
            <div style="margin-bottom: 10px;">Bandung, {{ \Carbon\Carbon::parse($data['penilaian']->created_at)->format('d F Y') }}</div>
            <div style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;">PENILAI</div>
            @if($data['qrCodeBase64'])
            <div class="qrcode">
                <img src="{{ $data['qrCodeBase64'] }}" alt="QR Code">
            </div>
            @endif
        </div>
    </div>
    
    @if($index < count($penilaianList) - 1)
    <div class="page-break"></div>
    @endif
    @endforeach
</body>
</html>
