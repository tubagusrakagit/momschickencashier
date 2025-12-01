<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font struk klasik */
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 300px; /* Lebar standar kertas struk thermal 80mm */
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .item span:first-child {
            flex: 1; /* Nama produk ambil sisa ruang */
        }
        .totals {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 4px;
        }
        .text-right {
            text-align: right;
        }
        
        /* Agar tombol cetak tidak ikut tercetak */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()"> <!-- Otomatis print saat dibuka -->

    <div class="header">
        <h2>MOM'S CHICKEN</h2>
        <p>Jl. Raya Ayam Goreng No. 1<br>Bogor, Jawa Barat</p>
        <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p>Kasir: {{ $transaction->user->name ?? 'Admin' }}</p>
        <p>No: {{ $transaction->invoice_number }}</p>
    </div>

    <div class="divider"></div>

    <!-- Daftar Item -->
    @foreach($transaction->details as $detail)
    <div class="item">
        <span>{{ $detail->product->name }}</span>
    </div>
    <div class="item">
        <span style="padding-left: 10px; color: #555;">{{ $detail->quantity }} x {{ number_format($detail->price_per_unit, 0, ',', '.') }}</span>
        <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="item">
        <span>Subtotal</span>
        <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
    </div>
    <div class="item">
        <span>Pajak (10%)</span>
        <span>{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
    </div>
    
    <div class="divider"></div>

    <div class="totals" style="font-size: 14px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
    </div>
    
    <div class="item" style="margin-top: 5px;">
        <span>Metode:</span>
        <span style="text-transform: uppercase;">{{ $transaction->payment_method }}</span>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>Terima Kasih<br>Selamat Menikmati!</p>
        <p class="no-print" style="margin-top: 20px;">
            <button onclick="window.print()" style="padding: 5px 10px; cursor: pointer;">Cetak Ulang</button>
        </p>
    </div>

</body>
</html>