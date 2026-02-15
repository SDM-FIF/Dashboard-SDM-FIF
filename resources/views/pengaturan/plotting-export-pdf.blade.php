<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Plotting Permission - {{ $role->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
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
            font-size: 18px;
            font-weight: bold;
        }
        
        .header .role-info {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
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
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #999;
            font-size: 10px;
        }
        
        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        table td:first-child {
            font-weight: bold;
            color: #C41E3A;
        }
        
        table td:nth-child(n+3) {
            text-align: center;
        }
        
        .ya {
            color: #28a745;
            font-weight: bold;
        }
        
        .tidak {
            color: #dc3545;
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
        <h1>PLOTTING PERMISSION HAK AKSES</h1>
        <div class="role-info">Role: {{ $role->name }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Modul Parent</th>
                <th style="width: 25%;">Sub Modul</th>
                <th style="width: 8%;">All</th>
                <th style="width: 8%;">View</th>
                <th style="width: 8%;">Detail</th>
                <th style="width: 8%;">Create</th>
                <th style="width: 8%;">Edit</th>
                <th style="width: 8%;">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissionData as $data)
            <tr>
                <td>{{ $data['parent_module'] }}</td>
                <td>{{ $data['sub_module'] }}</td>
                <td>
                    @if(isset($data['permissions']['all']))
                        <span class="{{ $data['permissions']['all'] === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $data['permissions']['all'] }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $viewValue = $data['permissions']['view'] ?? ($data['permissions']['access'] ?? null);
                    @endphp
                    @if($viewValue)
                        <span class="{{ $viewValue === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $viewValue }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(isset($data['permissions']['detail']))
                        <span class="{{ $data['permissions']['detail'] === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $data['permissions']['detail'] }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(isset($data['permissions']['create']))
                        <span class="{{ $data['permissions']['create'] === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $data['permissions']['create'] }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $editValue = $data['permissions']['edit'] ?? ($data['permissions']['submit'] ?? null);
                    @endphp
                    @if($editValue)
                        <span class="{{ $editValue === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $editValue }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(isset($data['permissions']['delete']))
                        <span class="{{ $data['permissions']['delete'] === 'Ya' ? 'ya' : 'tidak' }}">
                            {{ $data['permissions']['delete'] }}
                        </span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Dashboard SDM FIF - Generated on {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
