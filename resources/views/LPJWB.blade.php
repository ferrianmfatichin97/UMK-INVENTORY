<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Pertanggung Jawaban Uang Muka Kerja Umum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
       body {
            font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif, sans-serif;
            /* font-size: 10px; */
            /* margin: 12px; */
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
    <img src="<?php echo $image?>" width="250" height="40"/>
    <br />
    <br />

    <h5>SURAT PERTANGGUNG JAWABAN UANG MUKA KERJA UMUM</h5>
    <h6>Nomor : {{ $nomor }}</h6>

    <p>Dari : Staff Umum</p>
    <p>Kepada Yth : General Manajer Keuangan</p>
    <p>Perihal : Pertanggung Jawaban Uang Muka Kerja Umum</p>
    <br>

    <p>
        Menunjuk perihal diatas, dengan ini diajukan Pertanggung jawaban
        Uang Muka Kerja Umum Tanggal {{ $tanggal }} :
    </p>
    <p>
        Stempel Kasir/Teller dengan kelebihan sebesar Rp.{{ $selisih }} ({{ $terbilang }})
    </p>
    <p>Dengan rincian sebagai berikut :</p>

    <table class="table1">
        <thead>
            <tr class="border">
                <th class="warna">No</th>
                <th class="warna">Kode Akun</th>
                <th class="warna">Nama Akun</th>
                <th class="warna">Pengajuan</th>
                <th class="warna">Realisasi</th>
                <th class="warna">Selisih</th>
            </tr>
        </thead>
        <tbody class="border">
            @php
                $no = 1;
                $totalNominalSum = 0;
                $nominalpengajuan = $total_pengajuan;
                // $selisih = $remainingAmount;
            @endphp
            @foreach ($transaksis as $transaksi)
                        @php
                            $totalNominal = $transaksi->nominal ?? 0;
                            $totalNominalSum += $totalNominal;

                        @endphp
                        <tr class="border">
                            <td class="border">{{ $no++ }}</td>
                            <td class="border">{{ $transaksi->akun_bpr ?? 'N/A' }}</td>
                            <td class="border">{{ $transaksi->nama_akun }}</td>
                            <td class="border">{{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                            <td class="border">{{ number_format($totalNominal, 0, ',', '.') }}</td>
                            <td class="border">{{ number_format($transaksi->nominal - $totalNominal, 0, ',', '.') }}</td>
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
                <td class="border">{{ number_format($total_pengajuan - $totalNominalSum, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    <br />


    <p>
        Terlampir kami sampaikan bukti-bukti pengeluaran sesuai dengan ketentuan yang berlaku.
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
