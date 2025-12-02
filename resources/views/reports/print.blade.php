<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Moms Chicken</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .summary { margin-top: 20px; float: right; width: 40%; }
        .summary table { border: none; }
        .summary td { border: none; padding: 2px; }
        .summary .total { font-weight: bold; font-size: 14px; border-top: 1px solid #000; }

        @media print {
            @page { size: A4; margin: 1cm; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>MOMS CHICKEN POS</h1>
        <p>Laporan Penjualan Harian</p>
        <p>Periode: {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th>Ref</th>
                <th class="text-right">Total</th>
                <th class="text-center">Valid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $trx->created_at->format('H:i') }}</td>
                <td>{{ $trx->invoice_number }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->payment_method }}</td>
                <td>{{ $trx->payment_reference ?? '-' }}</td>
                <td class="text-right">{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                <td class="text-center">{{ $trx->is_verified ? 'Ya' : 'Tdk' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td>Total Transaksi:</td>
                <td class="text-right">{{ $transactions->count() }}</td>
            </tr>
            <tr>
                <td>Tunai:</td>
                <td class="text-right">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Non-Tunai:</td>
                <td class="text-right">Rp {{ number_format($totalNonTunai, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>TOTAL OMSET:</td>
                <td class="text-right">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>