<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('category')->latest()->get();

        return view('transactions.index', compact('transactions'));
    }   

    /**
     * Show the form for creating a new resource.
     */
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

        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name'     => 'Apip',
                'email'    => 'apip@example.com',
                'password' => bcrypt('password'),
            ]);
        }
        $validated['user_id'] = $user->id;

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
