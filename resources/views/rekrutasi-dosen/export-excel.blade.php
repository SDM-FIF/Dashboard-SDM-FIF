
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