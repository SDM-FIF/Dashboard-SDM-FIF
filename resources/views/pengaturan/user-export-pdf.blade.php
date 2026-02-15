<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data User - Dashboard SDM FIF</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #C41E3A;
        }
        
        .header h1 {
            margin: 0;
            color: #C41E3A;
            font-size: 20px;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead tr {
            background-color: #C41E3A;
            color: white;
        }
        
        table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #999;
            font-size: 11px;
        }
        
        table th:first-child {
            text-align: center;
            width: 60px;
        }
        
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        table td:first-child {
            text-align: center;
            font-weight: bold;
        }
        
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA USER</h1>
        <div class="subtitle">Dashboard SDM FIF</div>
    </div>
    
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
    
    <div class="footer">
        Dashboard SDM FIF - Generated on {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
