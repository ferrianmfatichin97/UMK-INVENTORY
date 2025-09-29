<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SURAT PERMINTAAN UANG MUKA KERJA UMUM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif;
        }

        p {
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
            border-collapse: collapse;
        }

        .table1 th,
        .table1 td {
            border: 1px solid black;
            padding: 4px;
        }

        .table1 th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .table1 td {
            vertical-align: middle;
        }

        .table1 .text-left {
            text-align: left;
        }

        .table1 .text-center {
            text-align: center;
        }

        .table1 .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <img src="<?php echo $image; ?>" width="250" height="40" />

    <br /><br />

    <h4>SURAT PEMBAYARAN UANG MUKA KERJA UMUM</h4>
    <h5>Nomor : <strong>{{ $nomor }}</strong></h5>

    <table style="width: 100%; margin-top: 15px; font-size: 12px; border-collapse: collapse;">
        <tr>
            <td style="width: 80px;">Dari</td>
            <td style="width: 10px;">:</td>
            <td>General Manajer Operasional</td>
        </tr>
        <tr>
            <td>Kepada Yth</td>
            <td>:</td>
            <td>General Manajer Keuangan</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>Uang Muka Kerja Umum</td>
        </tr>
    </table>

    <p>
        Menunjuk perihal di atas, dengan ini diajukan Uang Muka Kerja Umum sebesar Rp. 10.000.000,-
        ({{ $terbilang }}),
        dengan Nomor Pengajuan : {{ $nomor }}.
    </p>
    <p>Uang Muka tersebut membebani akun sebagai berikut :</p>

    <table class="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalNominalSum = 0;
            @endphp
            @foreach ($transaksis as $transaksi)
                @php
                    $jumlah = $transaksi->jumlah ?? 0;
                    $totalNominalSum += $jumlah;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $transaksi->kode_akun ?? 'N/A' }}</td>
                    <td class="text-left">- {{ $transaksi->nama_akun }}</td>
                    <td class="text-center">
                        {{ $jumlah == 0 ? '-' : number_format($jumlah, 0, ',', '.') }}
                    </td>
                    <td class="text-center">-</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center"><strong>Total</strong></td>
                <td class="text-center">
                    <strong>{{ $totalNominalSum == 0 ? '-' : number_format($totalNominalSum, 0, ',', '.') }}</strong>
                </td>
                <td class="text-center">-</td>
            </tr>
        </tfoot>
    </table>

    @php
        use Carbon\Carbon;
        $tanggal = Carbon::now();
        Carbon::setLocale('id');
        $formattedDate = $tanggal->translatedFormat('d F Y');
    @endphp

    <br />

    <p style="text-align: justify;">
        Kami bertanggung jawab mutlak sepenuhnya atas kebenaran dan keabsahan pengeluaran/transaksi ini,
        sesuai dengan Keputusan Direksi Nomor : KD-18/DPTSP-DIR/122023 tentang Uang Muka Kerja Umum Tanggal 22
        Desember 2023.
    </p>

    <p>
        Demikian, atas perhatian dan kerjasamanya diucapkan, Terima kasih.
    </p>

    <table style="width: 100%; margin-top: 30px; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 10px; text-align: center">Menyetujui</td>
            <td></td>
            <td style="padding: 10px; text-align: center">Bekasi, {{ $formattedDate }}</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 70px;">
                <div style="height: 70px;"></div>
            </td>
        </tr>
        <tr>
            <td style="text-align: center"><strong>Dwi Sulastri</strong></td>
            <td></td>
            <td style="text-align: center"><strong>Sri Handayani</strong></td>
        </tr>
    </table>


    <p>
        Sesuai dengan pengajuan surat pembayaran uang muka kerja umum nomor : {{ $nomor }}, telah diserahkan muka
        kerja
        sebesar Rp. 10.000.000,- Melalui Transfers ke Rekening MSO 01.101.03287 atas Nama Ade Erlangga pada tanggal {{ $formattedDate }}.
    </p>

    <table style="width: 100%; margin-top: 30px; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 10px; text-align: center">Menyerahkan</td>
            <td></td>
            <td style="padding: 10px; text-align: center">Bekasi, {{ $formattedDate }}</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 70px;">
                <div style="height: 70px;"></div>
            </td>
        </tr>
        <tr>
            <td style="text-align: center"><strong>Dini Dwi Utami</strong></td>
            <td></td>
            <td style="text-align: center"><strong>{{ $userName }}</strong></td>
        </tr>
    </table>
</body>

</html>
