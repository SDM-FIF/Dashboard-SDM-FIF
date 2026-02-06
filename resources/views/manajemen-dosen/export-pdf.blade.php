<!DOCTYPE html>
<html>
<head>
    <title>Data Dosen</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #C41E3A; color: white; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #C41E3A; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Dosen</h2>
        <p>Dashboard SDM FIF - {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Kode Dosen</th>
                <th>Nama</th>
                <th>JFA</th>
                <th>Lokasi Kerja</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dosen as $item)
            <tr>
                <td>{{ $item->nip }}</td>
                <td>{{ $item->kode_dosen }}</td>
                <td>{{ $item->front_title }} {{ $item->nama_lengkap }}, {{ $item->back_title }}</td>
                <td>{{ $item->jabatan }}</td>
                <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
                <td>{{ $item->status_pegawai ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
