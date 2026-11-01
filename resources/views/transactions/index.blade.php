<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Transaksi</title>
</head>
<body>
    <h1>Daftar Transaksi</h1>

    @if (session('success'))
        <div style="color: green; font-weight: bold; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('transactions.create') }}">+ Tambah Transaksi</a>

    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 15px; width: 100%;">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date }}</td>
                    <td>{{ $transaction->category->name }}</td>
                    <td>{{ strtoupper($transaction->category->type) }}</td>
                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>    
                    <td colspan="5" style="text-align: center;">Belum ada catatan transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>