<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;


class TransactionController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->transactions()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $transaction = Transaction::create($validated);

        return response()->json([
            'message' => 'Transaction registered',
            'object' => $transaction
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {  
        return $request->user()->transactions()->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTransactionRequest $request, string $id)
    {
        $transaction = $request->user()->transactions()->findOrFail($id);
        $validated = $request->validated();
        $transaction->update($validated);
        
        return response()->json([
            'message' => 'Transaction updated successfully',
            'object' => $transaction
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $transaction = $request->user()->transactions()->findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted'], 204);
    }
}

