<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Poin</title>
    <link rel="stylesheet" href="{{ asset('css/history-points.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    {{-- Header --}}
    @include('partials.header')

    <!-- Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <div class="points-container animate-card">
        <!-- Header row -->
        <div class="header-row">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="title"><i class="fas fa-trophy"></i> Riwayat Poin</h2>
        </div>

        <!-- Total Poin -->
        <div class="total-card">
            <strong>Total Poin:</strong>
            <span class="total-points">{{ number_format($totalPoints ?? 0, 0, ',', '.') }}</span>
        </div>

        <!-- Tabel -->
        <div class="table-wrapper">
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sumber</th>
                        <th>Referensi</th>
                        <th>Perubahan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $h)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $h->source }}</td>
                            <td>#{{ $h->reference_id }}</td>
                            <td class="{{ $h->points_change >= 0 ? 'text-green':'text-red' }}">
                                {{ number_format($h->points_change, 0, ',', '.') }}
                            </td>
                            <td>{{ $h->description }}</td>
                            <td>{{ $h->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">Belum ada riwayat poin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $histories->links() }}
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
</body>
</html>
