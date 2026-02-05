<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Microteaching & Interview</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
        }
        .info-table {
            margin-left: 40px;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 0;
        }
        table.nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table.nilai-table th,
        table.nilai-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        table.nilai-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        table.nilai-table td.left {
            text-align: left;
        }
        .rekomendasi {
            margin: 15px 0;
        }
        .rekomendasi-detail {
            margin-left: 40px;
            margin-top: 10px;
        }
        .rekomendasi-detail table {
            width: 100%;
        }
        .rekomendasi-detail td {
            padding: 3px 0;
        }
        .logo {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 120px;
        }
    </style>
</head>
<body>
    {{-- Logo --}}
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-telkom-university.png'))) }}" 
         class="logo" alt="Logo Telkom University">

    {{-- Header --}}
    <div class="header">
        <h2>BERITA ACARA</h2>
        <h2>MICROTEACHING & INTERVIEW</h2>
    </div>

    {{-- Content --}}
    <div class="content">
        <p>
            Pada hari ini, <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('l') }}</strong> tanggal 
            <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('d') }}</strong> bulan 
            <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('F') }}</strong> tahun 
            <strong>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->translatedFormat('Y') }}</strong>, pukul 
            <strong>{{ \Carbon\Carbon::parse($jadwal->waktu)->format('H.i') }}</strong> WIB secara 
            <strong>{{ $jadwal->metode_pelaksanaan }}</strong> di Fakultas Informatika, Universitas Telkom Jalan Telekomunikasi No.1 Terusan Buah Batu Bandung, telah dilaksanakan kegiatan Microteaching & Interview untuk calon dosen profesional :
        </p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 150px;">Nama</td>
            <td style="width: 20px;">:</td>
            <td><strong>{{ $calonDosen->nama }}</strong></td>
        </tr>
        <tr>
            <td>Bidang Keahlian</td>
            <td>:</td>
            <td><strong>{{ $calonDosen->bidang_keahlian ?? '-' }}</strong></td>
        </tr>
    </table>

    <div class="content">
        <p>Dengan nilai sebagai berikut :</p>
    </div>

    {{-- Table Nilai --}}
    <table class="nilai-table">
        <thead>
            <tr>
                <th style="width: 40px;">NO.</th>
                <th style="width: 200px;">PENGUJI</th>
                <th style="width: 120px;">NILAI RATA-RATA<br>BERBOBOT</th>
                <th>CATATAN PENILAI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaianList as $index => $penilaian)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td class="left">
                    @if($penilaian->dosen)
                        {{ $penilaian->dosen->front_title }} {{ $penilaian->dosen->nama_lengkap }}, {{ $penilaian->dosen->back_title }}
                    @else
                        -
                    @endif
                </td>
                <td><strong>{{ number_format($penilaian->rata_nilai, 2) }}</strong></td>
                <td class="left">{{ $penilaian->catatan_penilai ?? '-' }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>NILAI RATA-RATA AKHIR</strong></td>
                <td><strong>{{ number_format($nilaiRataAkhir, 2) }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Rekomendasi --}}
    <div class="rekomendasi">
        <p>
            Rekomendasi akhir dinyatakan : 
            <strong>{{ $penilaianDosenPenguji1->rekomendasi_akhir ? 'Direkomendasikan' : 'Tidak Direkomendasikan' }}</strong>
        </p>

        @if($penilaianDosenPenguji1->rekomendasi_akhir)
        <div class="rekomendasi-detail">
            <table>
                <tr>
                    <td style="width: 220px;">Prodi</td>
                    <td style="width: 20px;">:</td>
                    <td><strong>{{ $penilaianDosenPenguji1->prodiRekomendasi->nama_prodi ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><strong>{{ $penilaianDosenPenguji1->status_rekomendasi ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>JFA yang diakui</td>
                    <td>:</td>
                    <td><strong>{{ $penilaianDosenPenguji1->jfa_rekomendasi ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Pendidikan yang diakui</td>
                    <td>:</td>
                    <td><strong>{{ $penilaianDosenPenguji1->pendidikan_rekomendasi ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Kelompok Keahlian</td>
                    <td>:</td>
                    <td><strong>{{ $penilaianDosenPenguji1->kkRekomendasi->nama_kelompok_keahlian ?? '-' }}</strong></td>
                </tr>
            </table>
        </div>
        @endif
    </div>

    <div class="content" style="margin-top: 30px;">
        <p>Berita acara ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>
</body>
</html>
