<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userID = Auth::id();

        // 1. Inisialisasi Query dasar (terkunci per user)
        $query = Transaction::where('user_id', $userID);

        // 2. Filter Pencarian berdasarkan Deskripsi (Jika diisi)
        $query->when($request->search, function ($q) use ($request) {
            $q->where('description', 'like', '%' . $request->search . '%');
        });

        // 3. Filter berdasarkan Kategori (Jika dipilih)
        $query->when($request->category_id, function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        // 4. Filter berdasarkan Rentang Tanggal (Jika diisi)
        $query->when($request->start_date, function ($q) use ($request) {
            $q->whereDate('date', '>=', $request->start_date);
        });

        $query->when($request->end_date, function ($q) use ($request) {
            $q->whereDate('date', '<=', $request->end_date);
        });

        // 5. Kalkulasi Agregasi Keuangan mengikuti hasil filter (menggunakan clone query)
        $totalIncome = (clone $query)->whereHas('category', function ($q) {
            $q->where('type', 'income');
        })->sum('amount');

        $totalExpense = (clone $query)->whereHas('category', function ($q) {
            $q->where('type', 'expense');
        })->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // 6. Eksekusi Query dengan Pagination & Mempertahankan Query String
        $transactions = $query
            ->with('category')
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Memastikan parameter search/filter tidak hilang di halaman 2

        // Mengambil semua kategori untuk dropdown filter
        $categories = Category::all();

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }   

    
    public function create()
    {
        $categories = Category::all();
        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'amount'      => 'required|numeric|min:1',
        'date'        => 'required|date',
        'description' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Catatan transaksi berhasil disimpan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi ini.');
        }

        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah transaksi ini.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount'      => 'required|numeric|min:1',
            'date'        => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success','catatan transaksi berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus transaksi ini.');
        }
        
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success','catatan transaksi berhasil dihapus!');
    }
}
