<?php

namespace App\Http\Controllers\Central\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Central\Transaction\TransactionRequest;
use App\Models\Central\Tenant\Tenant;
use App\Models\Central\Transaction\Transaction;
use App\Models\Tenant\Promocode\Promocode;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $transactions], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $tenant = Tenant::find($request->input('tenant_id'));
        $transaction_data = $request->validated();
        $transaction_data['transaction_date'] = now()->format('Y-m-d');
        $transaction_data['balance'] = $tenant->due_amount - $transaction_data['transaction_amount'];
        $transaction_data['next_transaction_date'] = now()->addDays(7)->format('Y-m-d');

        Transaction::create($transaction_data);

        $tenant->due_amount = $transaction_data['balance'];
        $tenant->due_date = $transaction_data['next_transaction_date'];
        $tenant->save();

        return response()->json(['message' => 'تمت عملية التحويل بنجاح.'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $tenant_id)
    {
        $transactions = Transaction::where('tenant_id', $tenant_id)->filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $transactions], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
