<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi UMK</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif; /* kompatibel untuk PDF */
            font-size: 11px;
            margin: 20px;
        }

        h4 {
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        p {
            margin: 0;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            font-size: 10px;
            margin-top: 20px;
            text-align: right;
            color: gray;
        }
    </style>
</head>
<body>
    <h4>Laporan Transaksi UMK</h4>
    {{-- <p><strong>No UMK:</strong> {{ $details->first()->NO_UMK ?? '-' }}</p> --}}

    <table>
        <thead>
            <tr>
                <th>Kode Pengajuan</th>
                <th>Akun BPR</th>
                <th>No Urut</th>
                <th>Nama Akun</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJumlah = 0; @endphp
            @foreach ($details as $row)
                <tr>
                    <td>{{ $row->NO_UMK }}</td>
                    <td>{{ $row->AKUN_BPR }}</td>
                    <td class="text-center">{{ $row->NO_URUT }}</td>
                    <td>{{ $row->NAMA_AKUN }}</td>
                    <td>{{ $row->KETERANGAN }}</td>
                    <td class="text-right">
                        Rp {{ number_format($row->TOTAL, 0, ',', '.') }}
                        @php $totalJumlah += $row->TOTAL; @endphp
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right"><strong>Total</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalJumlah, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }} oleh {{ $userName }}
    </p>
</body>
</html>
