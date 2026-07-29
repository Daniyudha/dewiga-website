<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $totalDebit = (clone $query)->sum('debit');
        $totalCredit = (clone $query)->sum('credit');
        $saldoAkhir = $totalDebit - $totalCredit;

        $latest = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->first();
        $saldoBerjalan = $latest ? $latest->balance : 0;

        return view('admin.transactions.index', compact('transactions', 'totalDebit', 'totalCredit', 'saldoAkhir', 'saldoBerjalan'));
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['debit'] = $validated['debit'] ?? 0;
        $validated['credit'] = $validated['credit'] ?? 0;

        // Calculate balance
        $latestBalance = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->first();
        $lastBalance = $latestBalance ? $latestBalance->balance : 0;
        $validated['balance'] = $lastBalance + $validated['debit'] - $validated['credit'];

        Transaction::create($validated);

        return redirect()->route('admin.transactions.index')->with([
            'message' => 'Transaksi berhasil ditambahkan!',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['debit'] = $validated['debit'] ?? 0;
        $validated['credit'] = $validated['credit'] ?? 0;

        $transaction->update($validated);

        // Recalculate all balances after this point
        $this->recalculateBalances();

        return redirect()->route('admin.transactions.index')->with([
            'message' => 'Transaksi berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        // Recalculate all balances
        $this->recalculateBalances();

        return redirect()->route('admin.transactions.index')->with([
            'message' => 'Transaksi berhasil dihapus!',
            'alert-type' => 'success',
        ]);
    }

    private function recalculateBalances()
    {
        $transactions = Transaction::orderBy('date', 'asc')->orderBy('created_at', 'asc')->get();
        $balance = 0;
        foreach ($transactions as $t) {
            $balance += $t->debit - $t->credit;
            $t->update(['balance' => $balance]);
        }
    }
}