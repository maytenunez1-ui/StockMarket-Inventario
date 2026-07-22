<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(): View
    {
        $loans = Loan::query()
            ->with(['book.author', 'user'])
            ->latest()
            ->paginate(12);

        return view('admin.loans.index', compact('loans'));
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pendiente', 'prestado', 'devuelto', 'atrasado'])],
            'notes' => ['nullable', 'string'],
        ]);

        $previousStatus = $loan->status;
        $book = $loan->book;

        if ($previousStatus !== 'devuelto' && $validated['status'] === 'devuelto') {
            $book->increment('stock');
            $validated['returned_date'] = Carbon::today();
        }

        if ($previousStatus === 'devuelto' && $validated['status'] !== 'devuelto') {
            if ($book->stock < 1) {
                return back()->with('error', 'No hay stock suficiente para reabrir esta compra.');
            }

            $book->decrement('stock');
            $validated['returned_date'] = null;
        }

        if ($validated['status'] !== 'devuelto' && $previousStatus !== 'devuelto') {
            $validated['returned_date'] = null;
        }

        $loan->update($validated);

        return back()->with('success', 'Compra actualizada correctamente.');
    }
}
