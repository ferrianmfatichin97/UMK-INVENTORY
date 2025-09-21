<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap ALL Transaksi UMK</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            font-size: 11px;
        }

        th,
        td {
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

        .subtotal {
            font-weight: bold;
            background: #f9f9f9;
        }

        .grand-total {
            font-weight: bold;
            background: #eaeaea;
        }
    </style>
</head>

<body>
    <h4 style="text-align: center;">Laporan Transaksi UMK</h4>
    <p><strong>No UMK:</strong> {{ $details->first()->NO_UMK ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Akun BPR</th>
                <th>No Urut</th>
                <th>Nama Akun</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentAkun = null;
                $subtotal = 0;
                $grandTotal = 0;
            @endphp

            @foreach ($details as $row)
                {{-- Jika ganti akun_bpr, tampilkan subtotal --}}
                @if ($currentAkun !== null && $row->AKUN_BPR !== $currentAkun)
                    <tr class="subtotal">
                        <td colspan="4" class="text-right">{{ $currentAkun }} Total</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $subtotal = 0; @endphp
                @endif

                {{-- Row detail --}}
                <tr>
                    <td>{{ $row->AKUN_BPR }}</td>
                    <td class="text-center">{{ $row->NO_URUT }}</td>
                    <td>{{ $row->NAMA_AKUN }}</td>
                    <td>{{ $row->KETERANGAN }}</td>
                    <td class="text-right">
                        Rp {{ number_format($row->TOTAL, 0, ',', '.') }}
                        @php
                            $subtotal += $row->TOTAL;
                            $grandTotal += $row->TOTAL;
                        @endphp
                    </td>
                </tr>

                @php $currentAkun = $row->AKUN_BPR; @endphp
            @endforeach

            {{-- Subtotal terakhir --}}
            @if ($currentAkun !== null)
                <tr>
                    <td colspan="4" style="text-align:right"><strong>{{ $currentAkun }}</strong> Total</td>
                    <td style="text-align:right"><strong>Rp. {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                </tr>

            @endif

            {{-- Grand total --}}
            <tr class="grand-total">
                <td colspan="4" class="text-right">Grand Total</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:10px; margin-top:20px; text-align:right; color:gray;">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }} oleh {{ $userName }}
    </p>
</body>

</html>
