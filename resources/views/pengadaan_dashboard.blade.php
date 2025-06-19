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
        }
    </style>
</head>

{{-- <body class="bg-light"> --}}

<body class="bg-light min-vh-100 d-flex flex-column">

    <div class="container py-5">
        <h1 class="mb-4 text-center fw-bold text-primary">📊 Dashboard Pengadaan Barang</h1>
        <h5 class="mb-4 text-center fw-bold text-primary">Divisi UMUM Bank DP Taspen</h5>

        {{-- Ringkasan Statistik --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Pengadaan</h6>
                        <h3 class="fw-bold text-dark">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                {{-- <div class="card border-0 shadow-sm bg-info text-white"> --}}
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h6>Diproses</h6>
                        <h3 class="fw-bold">{{ $stats['diproses'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Selesai</h6>
                        <h3 class="fw-bold">{{ $stats['selesai'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h6>Ditolak</h6>
                        <h3 class="fw-bold">{{ $stats['ditolak'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Pengadaan --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                Pengadaan Terbaru
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Divisi</th>
                                <th>Urgensi</th>
                                <th>Nota Dinas</th>
                                <th>Nama Barang</th>
                                <th>Nota Dinas</th>
                                <th>Tanggal Dibutuhkan</th>
                                <th>Sisa Hari</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengadaans as $item)
                                <tr class="text-center">
                                    <td>{{ $item->divisi }}</td>
                                    <td>
                                        <span
                                            class="badge-urgensi status-badge bg-{{ match ($item->urgensi) {
                                                'tinggi' => 'danger',
                                                'sedang' => 'warning',
                                                'rendah' => 'success',
                                            } }}">
                                            {{ ucfirst($item->urgensi) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->nota_dinas }}</td>
                                    <td>
                                        @if ($item->details->isNotEmpty())
                                            <ul class="mb-0 text-start">
                                                @foreach ($item->details as $detail)
                                                    <li>{{ $detail->nama_barang }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->lampiran_nodin)
                                            <a href="{{ Storage::url($item->lampiran_nodin) }}" target="_blank">
                                                <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/icons/file-earmark-pdf.svg"
                                                    alt="PDF" width="24">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        @if ($item->status === 'selesai')
                                            <span class="text-muted">-</span>
                                        @else
                                            @php
                                                $today = \Carbon\Carbon::today();
                                                $deadline = \Carbon\Carbon::parse(
                                                    $item->tanggal_dibutuhkan,
                                                )->startOfDay();
                                                $daysLeft = $today->diffInDays($deadline, false);
                                            @endphp

                                            @if ($daysLeft < 0)
                                                <span class="text-danger fw-semibold">Lewat {{ abs($daysLeft) }}
                                                    hari</span>
                                            @elseif ($daysLeft === 0)
                                                <span class="text-warning fw-semibold">Hari ini</span>
                                            @else
                                                <span class="text-success fw-semibold">{{ $daysLeft }} hari
                                                    lagi</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data pengadaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <footer class="bg-dark bg-opacity-75 text-white text-center py-3 mt-auto">
        <div class="container">
            <small>© {{ now()->year }} Divisi IT – Bank DP Taspen. All rights reserved.</small>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
