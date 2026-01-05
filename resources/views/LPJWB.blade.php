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
            font-family: 'Gill Sans', 'Gill Sans MT', 'Trebuchet MS', sans-serif;
        }

        p {
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
        }

        h5,
        h6 {
            text-align: center;
            margin: 0;
        }

        .table1 {
            width: 100%;
            margin-top: 10px;
            border: 1px solid black;
            font-size: 12px;
            border-collapse: collapse;
        }

        .table1 th,
        .table1 td {
            border: 1px solid black;
            padding: 4px;
        }

        .warna {
            background-color: #f2f2f2;
        }

        /* Center khusus kolom angka */
        .angka {
            text-align: center;
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 250px;
        }
    </style>
</head>

<body>
    <img src="<?php echo $image; ?>" width="250" height="40" />
    <br /><br />

    <h5>SURAT PERTANGGUNG JAWABAN UANG MUKA KERJA UMUM</h5>
    <h6>Nomor : {{ $nomor }}</h6>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px;">
        <tr>
            <td style="width: 80px;">Dari</td>
            <td style="width: 10px;">:</td>
            <td>General Manager Operasional</td>
        </tr>
        <tr>
            <td>Kepada Yth</td>
            <td>:</td>
            <td>General Manajer Keuangan</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>Pertanggung Jawaban Uang Muka Kerja Umum</td>
        </tr>
    </table>

    <br />

    <p>
        Menunjuk perihal diatas, dengan ini diajukan Pertanggung jawaban
        Uang Muka Kerja Umum Tanggal {{ $tanggal_pengajuan }} :
    </p>
    <p>
        dengan Nomor Pengajuan : <strong>{{ $nomor }}</strong>.
    </p>
    <p>
        Stempel Kasir/Teller dengan kelebihan sebesar Rp.{{ $total_selisih }} ({{ $terbilang }})
    </p>
    <p>Dengan rincian sebagai berikut :</p>

    <table class="table1">
        <thead>
            <tr>
                <th class="warna">No</th>
                <th class="warna">Kode Akun</th>
                <th class="warna">Nama Akun</th>
                <th class="warna">Pengajuan</th>
                <th class="warna">Realisasi</th>
                <th class="warna">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($transaksis as $transaksi)
                @php
                    $selisih = $transaksi['Pengajuan'] - $transaksi['Transaksi'];
                @endphp
                <tr>
                    <td class="angka">{{ $no++ }}</td>
                    <td class="angka">{{ $transaksi['A'] }}</td>
                    <td>- {{ $transaksi['B'] }}</td>
                    <td class="angka">
                        {{ $transaksi['Pengajuan'] == 0 ? '-' : number_format($transaksi['Pengajuan'], 0, ',', '.') }}
                    </td>
                    <td class="angka">
                        {{ $transaksi['Transaksi'] == 0 ? '-' : number_format($transaksi['Transaksi'], 0, ',', '.') }}
                    </td>
                    <td class="angka">
                        @if ($selisih == 0)
                            -
                        @elseif ($selisih < 0)
                            ({{ number_format(abs($selisih), 0, ',', '.') }})
                        @else
                            {{ number_format($selisih, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php $totalSelisih = $total_selisih; @endphp
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td class="angka">{{ $total_pengajuan == 0 ? '-' : number_format($total_pengajuan, 0, ',', '.') }}
                </td>
                <td class="angka">{{ $total_realisasi == 0 ? '-' : number_format($total_realisasi, 0, ',', '.') }}
                </td>
                <td class="angka">
                    @if ($totalSelisih == 0)
                        -
                    @elseif ($totalSelisih < 0)
                        ({{ number_format(abs($totalSelisih), 0, ',', '.') }})
                    @else
                        {{ number_format($totalSelisih, 0, ',', '.') }}
                    @endif
                </td>
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

    <table style="width: 100%; margin-top: 25px; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Menyetujui</p>
            </td>
            <td style="padding: 20px; text-align: center"></td>
            <td style="padding: 20px; text-align: center">
                <p style="margin: 0">Bekasi, {{ $tanggal }}</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 40px; text-align: center"></td>
            <td style="padding: 40px; text-align: center"></td>
            <td style="padding: 40px; text-align: center"></td>
        </tr>
        <tr>
            <td style="padding: 10px; text-align: center">
                <p style="margin: 0"><strong> Dwi Sulastri</strong></p>
            </td>
            <td></td>
            <td style="padding: 10px; text-align: center">
                <table style="margin: 0 auto; border-collapse: collapse; font-size: 10px;">
                    <tr>
                        <td style="padding: 0 30px;"><strong>Sri Handayani</strong></td>
                        <td style="padding: 0 30px;"><strong>{{ $userName }}</strong></td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>

    <div style="position: fixed; bottom: 10px; right: 20px; font-size: 10px; font-style: italic;">
        Dicetak pada {{ $tanggal_cetak }} oleh <strong>{{ $userName }}</strong>
    </div>



</body>

</html>
