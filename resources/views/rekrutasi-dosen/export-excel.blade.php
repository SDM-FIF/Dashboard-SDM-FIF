
<table>
    <thead>
        <tr>
            <th>No. Registrasi</th>
            <th>Nama</th>
            <th>Jenjang</th>
            <th>Nama Prodi</th>
            <th>Tahun Ajar</th>
            <th>Jalur Lamaran</th>
            <th>H-Index</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekrutasi as $item)
        <tr>
            <td>{{ $item->no_registrasi }}</td>
            <td>{{ $item->nama_lengkap }}</td>
            <td>{{ strtoupper($item->prodi->jenjang ?? '-') }}</td>
            <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
            <td>{{ $item->tahunAjar->label ?? '-' }}</td>
            <td>{{ $item->jalur_lamaran ?? '-' }}</td>
            <td>{{ $item->h_index ?? '-' }}</td>
            <td>{{ $item->status_penerimaan ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>