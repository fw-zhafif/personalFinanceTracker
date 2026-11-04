<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $userID = Auth::id();

        #total pendapatan
        $totalIncome = Transaction::whereHas('category', function ($query) {
            $query->where('type','income');
        })->sum('amount');

        $totalExpense = Transaction::whereHas('category', function ($query) {
            $query->where('type','expense');
        })->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $transactions = Transaction::where('user_id', $userID)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact(
            'transactions',
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
