<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    <div class="container-transaksi">
        <h2 class="title">Riwayat Transaksi</h2>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($transactions->isEmpty())
            <div class="card empty">
                <p>Belum ada transaksi.</p>
            </div>
        @else
            @foreach($transactions as $trx)
                <div class="card transaksi-card">
                    <div class="trx-header">
                        <div>
                            <strong>Transaksi #{{ $trx->id }}</strong>
                            <div class="muted">
                                Tanggal: {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div class="trx-amount">
                            <div class="total">Rp {{ number_format($trx->total_amount,0,',','.') }}</div>
                            <div class="muted">Status: {{ ucfirst($trx->status) }}</div>
                        </div>
                    </div>

                    <div class="trx-items">
                        <table>
                            <thead>
                                <tr>
                                    <th>Jenis Sampah</th>
                                    <th>Qty (Kg)</th>
                                    <th>Harga/kg</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trx->items as $item)
                                    <tr>
                                        <td>
                                            {{ optional($item->type)->type_name ?? 'Tipe (ID: '.$item->waste_type_id.')' }}
                                            @if(optional($item->type)->category)
                                                <div class="muted small">Kategori: {{ $item->type->category->category_name }}</div>
                                            @endif
                                        </td>
                                        <td class="center">{{ $item->quantity }}</td>
                                        <td class="right">Rp {{ number_format($item->price_per_unit,0,',','.') }}</td>
                                        <td class="right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="trx-footer">
                        <div class="muted">
                            Subtotal: Rp {{ number_format(collect($trx->items)->sum('subtotal'),0,',','.') }}
                        </div>
                        @if(isset($trx->shipping_cost))
                            <div class="muted">
                                Ongkir: Rp {{ number_format($trx->shipping_cost,0,',','.') }}
                            </div>
                        @endif
                        <div class="total">
                            Total: Rp {{ number_format($trx->total_amount,0,',','.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</body>
</html>
