<table>
    <thead>
        <tr>
            <th>NIP</th>
            <th>Kode Dosen</th>
            <th>Nama Lengkap</th>
            <th>JFA</th>
            <th>Kelompok Keahlian</th>
            <th>Lokasi Kerja</th>
            <th>Status Pegawai</th>
            <th>Username</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dosen as $item)
        <tr>
            <td>{{ $item->nip }}</td>
            <td>{{ $item->kode_dosen }}</td>
            <td>{{ $item->front_title }} {{ $item->nama_lengkap }}, {{ $item->back_title }}</td>
            <td>{{ $item->jabatan }}</td>
            <td>{{ $item->kelompokKeahlian->nama_kelompok_keahlian ?? '-' }}</td>
            <td>{{ $item->prodi->nama_prodi ?? $item->lokasi_kerja }}</td>
            <td>{{ $item->status_pegawai }}</td>
            <td>{{ $item->user->username ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
