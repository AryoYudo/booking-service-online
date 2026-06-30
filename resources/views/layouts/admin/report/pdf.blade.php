<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
    font-family:sans-serif;
}

.header{
    text-align:center;
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#dc3545;
    color:white;
}

table th,
table td{
    border:1px solid #ddd;
    padding:10px;
}

</style>

</head>

<body>

<div class="header">

    <h2>
        LAPORAN BOOKING SERVICE HONDA
    </h2>

    <p>
        Dicetak :
        {{ now()->format('d M Y H:i') }}
    </p>

</div>

<table>

    <thead>

        <tr>

            <th>Customer</th>
            <th>Plat</th>
            <th>Service</th>
            <th>Status</th>
            <th>Tanggal</th>

        </tr>

    </thead>

    <tbody>

        @foreach($data as $row)

        <tr>

            <td>{{ $row->customer_name }}</td>
            <td>{{ $row->plate_number }}</td>
            <td>{{ $row->type_service }}</td>
            <td>{{ $row->status }}</td>
            <td>{{ $row->service_date }}</td>

        </tr>

        @endforeach

    </tbody>

</table>

<br><br><br>

<div style="text-align:right">

    Manager Operasional

    <br><br><br><br>

    ____________________

</div>

</body>
</html>