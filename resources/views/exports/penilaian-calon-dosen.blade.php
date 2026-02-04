<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Form Penilaian Calon Dosen</title>
</head>
<body>
<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 16px; font-weight: bold; padding: 20px 0;">
                FORM PENILAIAN CALON DOSEN
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 14px; padding: 10px 0;">
                TELKOM UNIVERSITY
            </th>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr>
            <td colspan="2"><strong>Nama Calon Dosen</strong></td>
            <td colspan="2">: {{ $calonDosen->nama }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Program Studi</strong></td>
            <td colspan="2">: {{ $calonDosen->prodi->nama_prodi ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Tahun Ajaran</strong></td>
            <td colspan="2">: {{ $jadwal->tahunAjar->label ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Tanggal Pengujian</strong></td>
            <td colspan="2">: {{ \Carbon\Carbon::parse($jadwal->tanggal_pengujian)->format('d F Y') }}</td>
        </tr>
        <tr><td colspan="4"></td></tr>
    </thead>
    <tbody>
        <tr style="background-color: #f0f0f0;">
            <th style="text-align: center; font-weight: bold;">No</th>
            <th style="text-align: left; font-weight: bold;">Kriteria Penilaian</th>
            <th style="text-align: center; font-weight: bold;">Nilai</th>
            <th style="text-align: center; font-weight: bold;">Keterangan</th>
        </tr>
        
        <!-- Section A: KUALIFIKASI (40%) -->
        <tr style="background-color: #d9ead3;">
            <td colspan="4" style="font-weight: bold;">A. KUALIFIKASI (40%)</td>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>Jalur Lamaran / Pendidikan = {{ $calonDosen->jalur_lamaran ?? '-' }}</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_jalur_lamaran, 2) }}</td>
            <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold;">Rata-rata A = {{ number_format($penilaian->rata_a, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td>Jabatan Fungsional Akademik (JFA) = {{ $calonDosen->jabatan_fungsional_akademik ?? 'NJFA' }}</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_jfa, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td>H-Index = {{ $calonDosen->h_index ?? 0 }}</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_h_index, 2) }}</td>
        </tr>
        
        <!-- Section B: MICRO TEACHING (20%) -->
        <tr style="background-color: #d9ead3;">
            <td colspan="4" style="font-weight: bold;">B. MICRO TEACHING (20%)</td>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>Penguasaan materi & audiens</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_pma, 2) }}</td>
            <td rowspan="3" style="text-align: center; vertical-align: middle; font-weight: bold;">Rata-rata B = {{ number_format($penilaian->rata_b, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td>Sistematika (kemudahan dipahami)</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_sistematika, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td>Kejelasan suara & tulisan</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kst, 2) }}</td>
        </tr>
        
        <!-- Section C: WAWANCARA (40%) -->
        <tr style="background-color: #d9ead3;">
            <td colspan="4" style="font-weight: bold;">C. WAWANCARA (40%)</td>
        </tr>
        <tr>
            <td style="text-align: center;">1</td>
            <td>Motivasi</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_motivasi, 2) }}</td>
            <td rowspan="8" style="text-align: center; vertical-align: middle; font-weight: bold;">Rata-rata C = {{ number_format($penilaian->rata_c, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">2</td>
            <td>Kemampuan mengajar</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmp_mengajar, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">3</td>
            <td>Kemampuan mengembangkan kurikulum Pengajaran</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmp_mkp, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">4</td>
            <td>Kemampuan penelitian & publikasi</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmp_pp, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">5</td>
            <td>Kemampuan Abdimas</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmp_abdimas, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">6</td>
            <td>Kemampuan Bekerjasama dengan tim</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmp_bdt, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">7</td>
            <td>Keahlian lainnya</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_keahlian_lainnya, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">8</td>
            <td>Komitmen waktu dan kesediaan melakukan hal diluar tugas pokok</td>
            <td style="text-align: center;">{{ number_format($penilaian->nilai_kmt_wkm, 2) }}</td>
        </tr>
        
        <!-- TOTAL NILAI -->
        <tr style="background-color: #cfe2f3;">
            <td colspan="2" style="text-align: center; font-weight: bold;">TOTAL NILAI (Rata-rata Berbobot)</td>
            <td style="text-align: center; font-weight: bold;">{{ number_format($penilaian->rata_nilai, 2) }}</td>
            <td style="text-align: center; font-weight: bold;">{{ $penilaian->keterangan_berbobot }}</td>
        </tr>
        
        <!-- Kesiapan & Kesediaan -->
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">Kesiapan bergabung segera?</td>
            <td colspan="2" style="text-align: center;">{{ $penilaian->kesiapan ? 'YA' : 'TIDAK/PIKIR-PIKIR' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; font-weight: bold;">Bersedia dengan standard gaji?</td>
            <td colspan="2" style="text-align: center;">{{ $penilaian->kesediaan ? 'YA' : 'TIDAK/PIKIR-PIKIR' }}</td>
        </tr>
        
        <tr><td colspan="4"></td></tr>
        
        <!-- Catatan -->
        @if($penilaian->catatan_penilai)
        <tr>
            <td colspan="4" style="font-weight: bold;">Catatan/Komentar:</td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 10px;">{{ $penilaian->catatan_penilai }}</td>
        </tr>
        @endif
        
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>
        
        <!-- Section Pengesahan -->
        <tr>
            <td colspan="4" style="text-align: right; padding-right: 50px;">
                Bandung, {{ \Carbon\Carbon::parse($penilaian->created_at)->format('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">
                PENILAI
            </td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>
        <tr><td colspan="4"></td></tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                (Barcode akan muncul di sini)
            </td>
        </tr>
        <tr><td colspan="4"></td></tr>
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">
                {{ $dosenPenguji->front_title }} {{ $dosenPenguji->nama_lengkap }}, {{ $dosenPenguji->back_title }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                NIP: {{ $dosenPenguji->nip }}
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
