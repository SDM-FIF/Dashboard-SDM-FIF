<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Roles</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->nama_lengkap }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
