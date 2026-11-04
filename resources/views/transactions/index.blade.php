<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Transaksi</title>

    <style>
        /* Membatasi ukuran ikon SVG pada navigasi pagination */
        nav svg {
            max-width: 20px;
            max-height: 20px;
        }
        /* Merapikan susunan elemen pagination */
        nav .flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        nav .inline-flex {
            display: inline-flex;
            gap: 5px;
        }
    </style>
    
</head>
<body>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Personal Finance Tracker</h1>
        
        <div>
            Halo, <strong>{{ Auth::user()->name }}</strong> | 
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="cursor: pointer;">Logout</button>
            </form>
        </div>
    </div>

    {{-- 1. Kartu Ringkasan Keuangan --}}
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #e8f5e9;">
            <h3 style="margin: 0; color: green;">Total Pemasukan</h3>
            <p style="font-size: 20px; font-weight: bold; margin: 10px 0 0 0;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
        </div>

        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #ffebee;">
            <h3 style="margin: 0; color: red;">Total Pengeluaran</h3>
            <p style="font-size: 20px; font-weight: bold; margin: 10px 0 0 0;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </p>
        </div>

        <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; flex: 1; background-color: #e3f2fd;">
            <h3 style="margin: 0; color: #0d47a1;">Sisa Saldo</h3>
            <p style="font-size: 20px; font-weight: bold; margin: 10px 0 0 0;">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Flash Message --}}
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
                    <td>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('transactions.edit', $transaction->id) }}">Edit</a>

                        {{-- Form Hapus --}}
                        <form action="{{ route('transactions.destroy', $transaction->id) }}" 
                            method="POST" 
                            style="display: inline;" 
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>    
                    <td colspan="5" style="text-align: center;">Belum ada catatan transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- 2. Navigasi Pagination --}}
    <div style="margin-top: 15px;">
        {{ $transactions->links() }}
    </div>
</body>
</html>