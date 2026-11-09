<x-layouts.app>
    <x-slot:title>Edit Transaksi - Personal Finance Tracker</x-slot:title>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-100">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Catatan Transaksi</h2>
            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                ← Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-6 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Pilihan Kategori --}}
            <div>
                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="category_id" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ strtoupper($category->type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nominal --}}
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1">Nominal (Rp)</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $transaction->amount) }}" min="1" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Transaksi</label>
                <input type="date" name="date" id="date" value="{{ old('date', $transaction->date) }}" required 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan (Opsional)</label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('description', $transaction->description) }}</textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('transactions.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md text-sm transition">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md text-sm shadow transition">
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>