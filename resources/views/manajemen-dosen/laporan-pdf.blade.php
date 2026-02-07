<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dosen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #C41E3A;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #C41E3A;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-box {
            display: table-cell;
            width: 20%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            margin-right: 10px;
            background-color: #f9fafb;
        }
        .summary-box .label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .summary-box .value {
            font-size: 28px;
            font-weight: bold;
        }
        .summary-box.red .value { color: #DC2626; }
        .summary-box.green .value { color: #10B981; }
        .summary-box.blue .value { color: #3B82F6; }
        .summary-box.yellow .value { color: #F59E0B; }
        .summary-box.purple .value { color: #8B5CF6; }
        
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #C41E3A;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #C41E3A;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #C41E3A;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        table td.text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .two-column {
            width: 100%;
            margin-bottom: 20px;
        }
        .column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }
        .column:first-child {
            margin-right: 4%;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN DATA DOSEN</h1>
        <p>Sistem Dashboard SDM FIF</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-box red">
                <div class="label">Total Dosen</div>
                <div class="value">{{ $statistik['total_dosen'] }}</div>
            </div>
            <div class="summary-box green">
                <div class="label">Dosen Aktif</div>
                <div class="value">{{ $statistik['per_status_dosen']['aktif'] }}</div>
            </div>
            <div class="summary-box blue">
                <div class="label">Tugas Belajar</div>
                <div class="value">{{ $statistik['per_status_dosen']['tugas_belajar'] }}</div>
            </div>
            <div class="summary-box yellow">
                <div class="label">Izin Belajar</div>
                <div class="value">{{ $statistik['per_status_dosen']['izin_belajar'] }}</div>
            </div>
            <div class="summary-box purple">
                <div class="label">CLTY</div>
                <div class="value">{{ $statistik['per_status_dosen']['clty'] }}</div>
            </div>
        </div>
    </div>

    {{-- Distribusi Status Pegawai Section --}}
    <div class="section">
        <div class="section-title">Distribusi Status Pegawai</div>
        <table>
            <thead>
                <tr>
                    <th>Status Pegawai</th>
                    <th class="text-right" style="text-align: right;">Jumlah</th>
                    <th class="text-right" style="text-align: right;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tetap</td>
                    <td class="text-right">{{ $statistik['per_status']['tetap'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_status']['tetap'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Perbantuan</td>
                    <td class="text-right">{{ $statistik['per_status']['perbantuan'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_status']['perbantuan'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Profesional Full Time</td>
                    <td class="text-right">{{ $statistik['per_status']['profesional_full'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_status']['profesional_full'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Profesional Part Time</td>
                    <td class="text-right">{{ $statistik['per_status']['profesional_part'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_status']['profesional_part'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Distribusi JFA Section --}}
    <div class="section">
        <div class="section-title">Distribusi Jabatan Fungsional Akademik</div>
        <table>
            <thead>
                <tr>
                    <th>Jabatan Fungsional</th>
                    <th class="text-right" style="text-align: right;">Jumlah</th>
                    <th class="text-right" style="text-align: right;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>NJFA</td>
                    <td class="text-right">{{ $statistik['per_jfa']['njfa'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_jfa']['njfa'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Asisten Ahli</td>
                    <td class="text-right">{{ $statistik['per_jfa']['asisten_ahli'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_jfa']['asisten_ahli'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Lektor</td>
                    <td class="text-right">{{ $statistik['per_jfa']['lektor'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_jfa']['lektor'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Lektor Kepala</td>
                    <td class="text-right">{{ $statistik['per_jfa']['lektor_kepala'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_jfa']['lektor_kepala'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Guru Besar</td>
                    <td class="text-right">{{ $statistik['per_jfa']['guru_besar'] }}</td>
                    <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($statistik['per_jfa']['guru_besar'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="page-break-inside: avoid;">
        {{-- Two Column Layout --}}
        <div class="two-column">
            {{-- Dosen per Lokasi Kerja --}}
            <div class="column">
                <div class="section">
                    <div class="section-title">Dosen per Lokasi Kerja</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Lokasi Kerja</th>
                                <th class="text-right" style="text-align: right;">Jumlah</th>
                                <th class="text-right" style="text-align: right;">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_prodi'] as $prodi)
                            <tr>
                                <td>{{ $prodi['nama'] }}</td>
                                <td class="text-right">{{ $prodi['jumlah'] }}</td>
                                <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($prodi['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center;">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Dosen per Kelompok Keahlian --}}
            <div class="column">
                <div class="section">
                    <div class="section-title">Dosen per Kelompok Keahlian</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Kelompok Keahlian</th>
                                <th class="text-right" style="text-align: right;">Jumlah</th>
                                <th class="text-right" style="text-align: right;">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistik['per_kelompok_keahlian'] as $kelompok)
                            <tr>
                                <td>{{ $kelompok['nama'] }}</td>
                                <td class="text-right">{{ $kelompok['jumlah'] }}</td>
                                <td class="text-right">{{ $statistik['total_dosen'] > 0 ? round(($kelompok['jumlah'] / $statistik['total_dosen']) * 100, 1) : 0 }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center;">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh Sistem Dashboard SDM FIF</p>
        <p>© {{ date('Y') }} Fakultas Ilmu dan Teknologi - Semua hak dilindungi</p>
    </div>
</body>
</html>
