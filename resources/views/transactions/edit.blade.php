<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Transaksi</title>
</head>
<body>
    <h1>Edit Transaksi</h1>

    <a href="{{ route('transactions.index') }}">← Kembali ke Daftar Transaksi</a>
    <br><br>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="category_id">Kategori:</label><br>
            <select name="category_id" id="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }} ({{ strtoupper($category->type) }})
                    </option>
                @endforeach
            </select>
        </div>
        <br>

        <div>
            <label for="amount">Nominal (Rp):</label><br>
            <input type="number" name="amount" id="amount" 
                value="{{ old('amount', $transaction->amount) }}" required>
        </div>
        <br>

        <div>
            <label for="date">Tanggal:</label><br>
            <input type="date" name="date" id="date" 
                value="{{ old('date', $transaction->date) }}" required>
        </div>
        <br>

        <div>
            <label for="description">Keterangan (Opsional):</label><br>
            <textarea name="description" id="description" rows="3">{{ old('description', $transaction->description) }}</textarea>
        </div>
        <br>

        <button type="submit">Perbarui Transaksi</button>
    </form>
</body>
</html>