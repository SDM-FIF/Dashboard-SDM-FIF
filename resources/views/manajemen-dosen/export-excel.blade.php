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
        </tr>
    </thead>
    <tbody>
        @foreach($dosen as $item)
        <tr>
            <td data-format="@">{{ $item->nip }}</td>
            <td>{{ $item->kode_dosen }}</td>
            <td>@if($item->front_title){{ $item->front_title }} @endif{{ $item->nama_lengkap }}@if($item->back_title), {{ $item->back_title }}@endif</td>
            <td>{{ $item->jabatan }}</td>
            <td>{{ $item->kelompokKeahlian->nama_kelompok_keahlian ?? '-' }}</td>
            <td>{{ $item->prodi->nama_prodi ?? '-' }}</td>
            <td>{{ $item->status_pegawai }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
