<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SURAT PEMBAYARAN UANG MUKA KERJA UMUM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 12px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            text-align: center;
        }

        .table1 {
            width: 100%;
            margin-top: 20px;
            border: 1px solid black;
            /* text-align: center; */
            font-size: 12px;
        }

        .border {
            border: 1px solid black;
            text-align: center;
        }

        .warna {
            background-color: #f2f2f2;
            border: 1px solid black;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
            flex: 1;
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 250px;
        }
    </style>
</head>

<body>
    <img src="{{ asset('image/logo.png') }}" alt="Logo" class="logo" />
    <br />
    <br />

    <h5>SURAT PEMBAYARAN UANG MUKA KERJA UMUM</h5>
    <h6>Nomor : {{ $nomor }}</h6>

    <p>Dari : General Manager Operasional</p>
    <p>Kepada Yth : General Manajer Keuangan</p>
    <p>Perihal : Pertanggung Jawaban Uang Muka Kerja Umum</p>
    <br>

    <p>
        Menunjuk perihal diatas, dengan ini diajukan Uang Muka Kerja Umum sebesar Rp.{{ $total_pengajuan }} ({{ $terbilang }})
    </p>
    <p>Uang Muka Tersebut membebani akun sebagai berikut : :</p>

    <table class="table1">
        <thead>
            <tr class="border">
                <th class="warna">No</th>
                <th class="warna">Kode Akun</th>
                <th class="warna">Nama Akun</th>
                <th class="warna">Jumlah</th>
                <th class="warna">Keterangan</th>
            </tr>
        </thead>
        <tbody class="border">
            @php
                $no = 1;
                $totalNominalSum = 0;
            @endphp
            @foreach ($transaksis as $transaksi)
                        @php
                            $totalNominal = $transaksi->jumlah ?? 0;
                            $nominalpengajuan = $transaksi->jumlah ?? 0;
                            $remainingAmount = $nominalpengajuan - $totalNominal;
                            $totalNominalSum += $totalNominal;
                        @endphp
                        <tr class="border">
                            <td class="border">{{ $no++ }}</td>
                            <td class="border">{{ $transaksi->kode_akun ?? 'N/A' }}</td>
                            <td class="border">{{ $transaksi->nama_akun }}</td>
                            <td class="border">{{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                            <td class="border">{{ $transaksi->keterangan}}</td>
                        </tr>
            @endforeach
            @php
            use Carbon\Carbon;
                //$remainingTotal = $total_pengajuan - $totalNominalSum;

                $tanggal = Carbon::now();
                Carbon::setLocale('id');
                $formattedDate = $tanggal->translatedFormat('d F Y');
            @endphp
        </tbody>
        <tfoot>
            <tr class="border">
                <td class="border" colspan="3"><strong>Total</strong></td>
                <td class="border">{{ number_format($total_pengajuan, 0, ',', '.') }}</td>
                <td class="border">{{ number_format($totalNominalSum, 0, ',', '.') }}</td>
                <td class="border">{{ number_format($selisih, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <br />


    <p>
        Kami bertanggung jawab mutlak sepenuhnya atas kebenaran dan keabsahan pengeluaran/transaksi ini,
        sesuai dengan Keputusan Direksi Nomor : KD-18/DPTSP-DIR/122023 tentang Uang Muka Kerja Umum Tanggal 22 Desember 2023.
    </p>

    <p>
        Demikian, atas perhatian dan kerjasamanya diucapkan, Terima kasih.
    </p>

    <table style="width: 100%; border-color: #ffff; margin-top: 50px; border-collapse: collapse; border:none;">
        <tr>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Menyetujui</p>
            </td>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0"></p>
            </td>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Bekasi, {{ $formattedDate }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center"></td>
            <td style="padding: 20px; text-align: center"></td>
            <td style="padding: 20px; text-align: center"></td>
        </tr>
        <tr>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Dwi Sulastri</p>
            </td>
            <td style="padding: 20px; text-align: center"></td>
            <td style="padding: 10px; text-align: center">
                <p style="margin: 0">Sri Handayani <span style="margin-left: 50px"> </span>{{ $userName }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
