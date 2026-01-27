<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jadwal Pengujian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #C41E3A;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #C41E3A;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Jadwal Pengujian Calon Dosen</h1>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Calon Dosen</th>
                <th style="width: 20%;">Dosen Penguji</th>
                <th style="width: 12%;">Tahun Ajar</th>
                <th style="width: 10%;">Metode</th>
                <th style="width: 10%;">Gedung</th>
                <th style="width: 10%;">Ruangan</th>
                <th style="width: 13%;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwalList as $index => $jadwal)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $jadwal->calonDosen->nama }}</td>
                <td>
                    @foreach($jadwal->dosenPenguji as $dosen)
                        {{ $dosen->pivot->urutan }}. {{ $dosen->front_title }} {{ $dosen->nama_lengkap }}, {{ $dosen->back_title }}<br>
                    @endforeach
                </td>
                <td>{{ $jadwal->tahunAjar->label }}</td>
                <td>{{ $jadwal->metode_pelaksanaan }}</td>
                <td>{{ $jadwal->gedung ?? '-' }}</td>
                <td>{{ $jadwal->ruangan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($jadwal->jadwal_ujian)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($jadwal->waktu)->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i') }}
    </div>
</body>
</html>
