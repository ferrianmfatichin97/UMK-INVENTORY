<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: #007BFF;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .content {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #dddddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f4;
            font-size: 12px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>

<body>
    {{-- {{ print_r($reminderGroups) }}--}}
    {{-- {{ dd($reminderGroups, $today) }} --}}
    <div class="container">
        <div class="header">
            <h1>Reminder Pembayaran Pajak Kendaraan</h1>
            {{-- <h2>Hari ini tanggal : {{ $today['today'] }}</h2> --}}
            <h2>Hari ini tanggal : </h2>
        </div>
        {{-- @if (count($reminderGroups) > 0) --}}
        <div class="content">
            <p>Berikut adalah Data kendaraan yang perlu dibayarkan dalam {{ $reminderGroups['selisih_hari'] }} hari ke depan:</p>
            <table>
                <tr>
                    {{-- <th>No</th> --}}
                    <th>Jenis Kendaraan</th>
                    <th>Nomor Kendaraan</th>
                    <th>Kantor Cabang</th>
                    <th>Tanggal Jatuh Tempo Pajak</th>
                </tr>
                {{-- @foreach ($reminderGroups as $index => $kendaraan) --}}
                {{-- @foreach ($reminderGroups as $kendaraan) --}}

                <tr>
                    {{-- <td>{{ $index + 1 }}</td> --}}
                    {{-- <td>no</td> --}}
                    {{-- <td>{{ $kendaraan->jenis_kendaraan }}</td>
                    <td>{{ $kendaraan->no_registrasi }}</td>
                    <td>{{ $kendaraan->kantor_cabang }}</td>
                    <td>{{ $kendaraan->jadwal_pajak }}</td> --}}
                    <td>{{ $reminderGroups['jenis_kendaraan'] }}</td>
                    <td>{{ $reminderGroups['no_registrasi'] }}</td>
                    <td>{{ $reminderGroups['kantor_cabang'] }}</td>
                    <td>{{ $reminderGroups['jadwal_pajak'] }}</td>
                </tr>
                {{-- @endforeach --}}
            </table>
        </div>
        <div class="footer">
            <p>&copy; 2025 PT Bank BPR dp Taspen.</p>
        </div>
        {{-- @else
        <div class="content">
            <p>Tidak ada kendaraan yang akan jatuh tempo pajak dalam 10 hari ke depan.</p>
        </div>
        @endif --}}
    </div>
