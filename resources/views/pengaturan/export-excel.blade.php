<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Role</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
