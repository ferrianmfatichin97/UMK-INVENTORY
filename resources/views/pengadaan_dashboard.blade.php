<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengadaan Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .badge-urgensi {
            font-weight: 600;
            color: #fff;
        }
    </style>
</head>

{{-- <body class="bg-light"> --}}

<body class="bg-light min-vh-100 d-flex flex-column">

    <div class="container py-5">
        {{-- <h1 class="mb-4 text-center fw-bold text-primary">📊 Dashboard Pengadaan Barang</h1> --}}
        <h1 class="mb-4 text-center fw-bold text-primary">Dashboard Pengadaan Barang</h1>
        <h5 class="mb-4 text-center fw-bold text-primary">(Divisi Umum Bank DP Taspen)</h5>

        {{-- Ringkasan Statistik --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Pengadaan</h6>
                        <h3 class="fw-bold text-dark">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{-- <div class="card border-0 shadow-sm bg-info text-white"> --}}
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h6>Diproses</h6>
                        <h3 class="fw-bold">{{ $stats['diproses'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Selesai</h6>
                        <h3 class="fw-bold">{{ $stats['selesai'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Pengadaan --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                Pengadaan
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Divisi</th>
                                <th>Nomor Surat</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengadaans as $item)
                                @php
                                    $detailCount = $item->details->count();
                                @endphp
                                @foreach ($item->details as $index => $detail)
                                    <tr class="text-center">
                                        @if ($index === 0)
                                            <td rowspan="{{ $detailCount }}">{{ $item->divisi }}</td>
                                            <td rowspan="{{ $detailCount }}">{{ $item->nota_dinas }}</td>
                                            <td rowspan="{{ $detailCount }}">
                                                {{ \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->format('d/m/Y') }}
                                            </td>
                                        @endif
                                        <td class="text-start">{{ $loop->iteration }}. {{ $detail->nama_barang }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->catatan }}</td>
                                        @if ($index === 0)
                                            <td rowspan="{{ $detailCount }}">
                                                <span
                                                    class="status-badge text-white bg-{{ match ($item->status) {
                                                        'diproses' => 'warning',
                                                        'ditolak' => 'danger',
                                                        'selesai' => 'success',
                                                        default => 'secondary',
                                                    } }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data pengadaan.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <div class="p-3">
                        {{ $pengadaans->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <footer class="bg-dark bg-opacity-75 text-white text-center py-3 mt-auto">
        <div class="container">
            <small>© {{ now()->year }} || Create By : Divisi IT – Bank DP Taspen. All rights reserved.</small>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setInterval(function() {
            window.location.reload();
        }, 60000);
    </script>
</body>

</html>
