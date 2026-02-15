<table>
    <thead>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <th><strong>Modul Parent</strong></th>
            <th><strong>Sub Modul</strong></th>
            <th><strong>All</strong></th>
            <th><strong>View</strong></th>
            <th><strong>Detail</strong></th>
            <th><strong>Create</strong></th>
            <th><strong>Edit</strong></th>
            <th><strong>Delete</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissionData as $data)
        <tr>
            <td>{{ $data['parent_module'] }}</td>
            <td>{{ $data['sub_module'] }}</td>
            <td>{{ $data['permissions']['all'] ?? '-' }}</td>
            <td>{{ $data['permissions']['view'] ?? ($data['permissions']['access'] ?? '-') }}</td>
            <td>{{ $data['permissions']['detail'] ?? '-' }}</td>
            <td>{{ $data['permissions']['create'] ?? '-' }}</td>
            <td>{{ $data['permissions']['edit'] ?? ($data['permissions']['submit'] ?? '-') }}</td>
            <td>{{ $data['permissions']['delete'] ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
