
<table>
    <thead>
        <tr>
            <th>No. Registrasi</th>
            <th>Nama</th>
            <th>Jenjang</th>
            <th>Nama Prodi</th>
            <th>Tahun Ajar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekrutasi as $item)
        <tr>
            <td>{{ $item->no_registrasi }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ strtoupper($item->prodi->jenjang ?? '-') }}</td>
            <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
            <td>{{ $item->tahunAjar->label ?? '-' }}</td>
            <td>{{ $item->status_penerimaan ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>