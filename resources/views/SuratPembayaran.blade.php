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
            font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif, sans-serif;
         
        }

        p{
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
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
            margin-top: 8px;
            border: 1px solid black;
            font-size: 12px;
        }

        .border {
            border: 1px solid black;
            text-align: center;
        }

        .border1 {
            border: 1px solid black;
            text-align: left;
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
            margin: 0 auto;
            width: 250px;
        }
    </style>
</head>

<body>
    <img src="<?php echo $image?>" width="250" height="40"/>

    <br />
    <br />

    <h4>SURAT PEMBAYARAN UANG MUKA KERJA UMUM</h4>
    <h5>Nomor : <strong>{{ $nomor }}</strong></h5>

    <p>Dari : Staff Umum</p>
    <p>Kepada Yth : General Manajer Keuangan</p>
    <p>Perihal : Uang Muka Kerja Umum</p>

    <p>
        Menunjuk perihal diatas, dengan ini diajukan Uang Muka Kerja Umum sebesar Rp. 10.000.000,-
        ({{ $terbilang }}), dengan Nomor Pengajuan : {{ $nomor }}.
    </p>
    <p>Uang Muka Tersebut membebani akun sebagai berikut :</p>

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
                            <td class="border1"> - {{ $transaksi->nama_akun }}</td>
                            <td class="border">{{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                            <td class="border">-</td>
                        </tr>
            @endforeach
            @php
                use Carbon\Carbon;

                $tanggal = Carbon::now();
                Carbon::setLocale('id');
                $formattedDate = $tanggal->translatedFormat('d F Y');
            @endphp
        </tbody>
    </table>
    <br />


    <p style="text-align: justify;">
        Kami bertanggung jawab mutlak sepenuhnya atas kebenaran dan keabsahan pengeluaran/transaksi ini,
        sesuai dengan Keputusan Direksi Nomor : KD-18/DPTSP-DIR/122023 tentang Uang Muka Kerja Umum Tanggal 22 Desember
        2023.
    </p>

    <p>
        Demikian, atas perhatian dan kerjasamanya diucapkan, Terima kasih.
    </p>

    <table style="width: 100%; border-color: #ffff; margin-top: 20px; border-collapse: collapse; border:none;">
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
                <p style="margin: 0">Sri Handayani</p>
            </td>
        </tr>
    </table>
    <p>
        Sesuai dengan pengajuan surat pembayaran uang muka kerja umum nomor : {{ $nomor }} , telah diserahkan
        muka kerja sebesar Rp. 10.000.000,- pada tanggal {{ $formattedDate }}.
    </p>
    <table style="width: 100%; border-color: #ffff; margin-top: 20px; border-collapse: collapse; border:none;">
        <tr>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Menyerahkan</p>
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
                <p style="margin: 0">Renny Meysa</p>
            </td>
            <td style="padding: 20px; text-align: center"></td>
            <td style="padding: 10px; text-align: center">
                <p style="margin: 0">{{ $userName }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
