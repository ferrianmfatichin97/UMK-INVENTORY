<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi UMK</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        h4 {
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h4>LAPORAN TRANSAKSI UMK</h4>
    <p><strong>No UMK:</strong> {{ $details->first()->NO_UMK ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>No Urut</th>
                <th>Kode Pengajuan</th>
                <th>Akun BPR</th>
                <th>Nama Akun</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJumlah = 0; @endphp
            @foreach ($details as $row)
                <tr>
                    <td style="text-align:center">{{ $row->NO_URUT }}</td>
                    <td>{{ $row->NO_UMK }}</td>
                    <td>{{ $row->AKUN_BPR }}</td>
                    <td>{{ $row->NAMA_AKUN }}</td>
                    <td>{{ $row->KETERANGAN }}</td>
                    <td style="text-align:right">
                        {{ number_format($row->JUMLAH, 0, ',', '.') }}
                        @php $totalJumlah += $row->JUMLAH; @endphp
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="text-align:right"><strong>Total</strong></td>
                <td style="text-align:right"><strong>{{ number_format($totalJumlah, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
    <p style="font-size:10px; margin-top:20px; text-align:right; color:gray;">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }} oleh {{ $userName }}
    </p>
</body>

</html>
