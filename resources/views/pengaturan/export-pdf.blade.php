<!DOCTYPE html>
<html>
<head>
    <title>Data Pengaturan Hak Akses</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #C41E3A; color: white; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #C41E3A; }
        .id-column { text-align: center; width: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Pengaturan Hak Akses</h2>
        <p>Dashboard SDM FIF - {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th>Nama Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td class="id-column">{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
