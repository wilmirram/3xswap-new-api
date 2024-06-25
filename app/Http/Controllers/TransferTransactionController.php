<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferTransactionRequest;
use App\Models\TransferTransaction;
use Illuminate\Http\JsonResponse;

class TransferTransactionController extends Controller
{
    public function store(StoreTransferTransactionRequest $request): JsonResponse
    {
        $user = $request->user();
        TransferTransaction::create([
            ...$request->validated(),
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Transfer transaction created successfully.',
        ], 201);
    }
}
