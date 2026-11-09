<x-layouts.app>
    <x-slot:title>Dasbor Transaksi - Personal Finance Tracker</x-slot:title>

    {{-- 1. Kartu Ringkasan Keuangan (Metrics Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-green-50 border border-green-200 p-5 rounded-lg shadow-sm">
            <h3 class="text-xs font-semibold text-green-700 uppercase tracking-wider">Total Pemasukan</h3>
            <p class="text-2xl font-bold text-green-800 mt-2">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-red-50 border border-red-200 p-5 rounded-lg shadow-sm">
            <h3 class="text-xs font-semibold text-red-700 uppercase tracking-wider">Total Pengeluaran</h3>
            <p class="text-2xl font-bold text-red-800 mt-2">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-blue-50 border border-blue-200 p-5 rounded-lg shadow-sm">
            <h3 class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Sisa Saldo</h3>
            <p class="text-2xl font-bold text-blue-900 mt-2">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Notifikasi Flash Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 2. Form Filter & Search --}}
    <div class="bg-white p-5 rounded-lg shadow-md mb-6 border border-gray-100">
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Cari Keterangan</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Contoh: Kopi..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="category_id" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div>
                <label for="end_date" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition shadow">
                    Filter
                </button>
                <a href="{{ route('transactions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-3 rounded-md text-sm transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Header Tombol Tambah --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Catatan</h2>
        <a href="{{ route('transactions.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md text-sm shadow transition">
            + Tambah Transaksi
        </a>
    </div>

    {{-- 3. Tabel Transaksi --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-4 whitespace-nowrap">{{ $transaction->date }}</td>
                            <td class="py-3 px-4 font-medium text-gray-900">{{ $transaction->category->name }}</td>
                            <td class="py-3 px-4">
                                @if ($transaction->category->type === 'income')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        INCOME
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        EXPENSE
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold {{ $transaction->category->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $transaction->description ?? '-' }}</td>
                            <td class="py-3 px-4 text-center space-x-2 whitespace-nowrap">
                                <a href="{{ route('transactions.edit', $transaction->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    Edit
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                Belum ada catatan transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 4. Navigasi Pagination --}}
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</x-layouts.app>