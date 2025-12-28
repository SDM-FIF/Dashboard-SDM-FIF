<!DOCTYPE html>
<html>
<head>
    <title>Data Rekrutasi Dosen</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #C41E3A; color: white; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #C41E3A; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Rekrutasi Dosen</h2>
        <p>Dashboard SDM FIF - {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama Calon</th>
                <th>Prodi</th>
                <th>Tahun Ajar</th>
                <th>Tanggal Pengujian</th>
                <th>Jadwal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekrutasi as $item)
            <tr>
                <td>{{ $item->no_registrasi }}</td>
                <td>{{ $item->nama_calon }}</td>
                <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $item->tahun_ajar ?? '-' }}</td>
                <td>{{ $item->tanggal_pengujian ? $item->tanggal_pengujian->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->jadwal ?? '-' }}</td>
                <td>{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>