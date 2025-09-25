<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}?v={{ time() }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    <div class="container-transaksi">
        <h2 class="title">🧾 Riwayat Transaksi</h2>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        {{-- Jika kosong --}}
        @if($transactions->isEmpty())
            <div class="card empty">
                <p>Belum ada transaksi.</p>
            </div>
        @else
            @foreach($transactions as $trx)
                <div class="card transaksi-card">
                    {{-- Header --}}
                    <div class="trx-header">
                        <div class="trx-id">
                            <strong>#{{ $trx->id }}</strong>
                            <span class="status {{ strtolower($trx->status) }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </div>
                        <div class="trx-amount">
                            Rp {{ number_format($trx->total_amount,0,',','.') }}
                        </div>
                    </div>

                    {{-- Detail utama --}}
                    <div class="trx-body">
                        <p class="muted">
                            📅 {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y H:i') }}
                        </p>
                        <p class="muted">
                            💳 {{ strtoupper($trx->payment_method ?? '-') }}
                        </p>
                        <p class="muted">
                            🚚 {{ strtoupper($trx->shipping_method ?? 'pickup') }}
                            @if($trx->shipping_method === 'delivery')
                                — {{ $trx->receiver_name }} | {{ $trx->phone }} | {{ $trx->address }}
                            @endif
                        </p>

                        {{-- Item list --}}
                        <div class="trx-items">
                            @foreach($trx->items as $item)
                                <div class="item-row">
                                    <div>
                                        <div class="item-name">
                                            {{ optional($item->type)->type_name ?? 'Tipe (ID: '.$item->waste_type_id.')' }}
                                        </div>
                                        <div class="item-category">
                                            {{ optional(optional($item->type)->category)->category_name ?? '-' }}
                                        </div>
                                    </div>
                                    <div class="item-price">
                                        {{ $item->quantity }} Kg × Rp {{ number_format($item->price_per_unit,0,',','.') }}
                                        <div class="subtotal">Rp {{ number_format($item->subtotal,0,',','.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="trx-footer">
                        <div>Subtotal: Rp {{ number_format(collect($trx->items)->sum('subtotal'),0,',','.') }}</div>
                        <div>Ongkir: Rp {{ number_format($trx->shipping_cost ?? 0,0,',','.') }}</div>
                        <div class="grand-total">
                            Total: Rp {{ number_format($trx->total_amount,0,',','.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</body>
</html>
