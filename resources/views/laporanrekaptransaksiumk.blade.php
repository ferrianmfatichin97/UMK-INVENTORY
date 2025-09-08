<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Transaksi UMK</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .title {
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 10px;
    }
    </style>
</head>
<body>
    <img src="{{ $image }}" width="100">
    <h3 class="title">Laporan Rekap Transaksi UMK</h3>
    <p>No. UMK: {{ $nomor }}</p>
    <p>Tanggal Pengajuan: {{ $tanggal }}</p>

    <table>
        <thead>
            <tr>
                <th>AKUN BPR</th>
                <th>Nama Akun</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $row)
            <tr>
                <td>{{ $row->AKUN_BPR }}</td>
                <td>{{ $row->NAMA_AKUN }}</td>
                <td class="text-right">{{ number_format($row->TOTAL, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">Grand Total</th>
                <th class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <p><strong>Terbilang:</strong> {{ $terbilang }}</p>
    <p style="font-size:10px; margin-top:20px; color:gray;">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }} oleh {{ $userName }}
    </p>
</body>
</html>
