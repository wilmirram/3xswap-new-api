<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransferController extends Controller
{
    function store(Request $request)
    {
        $requestData = $request->validate([
            'recipient_wallet' => 'required',
            'token_address' => 'required',
            'amount' => 'required',
        ]);

        $user = $request->user();

        $response = Http::post(env('WALLET_SERVICE_API_URL') . '/wallet/transfer', [
            'user_id' => $user->id,
            'to_address' => $request->recipient_wallet,
            'token_address' => $request->token_address,
            'amount' => $request->amount
        ]);

        if ($response->status() !== 200) {
            return response()->json(['message' => 'Transfer failed', 'error' => $response->json()], 500);
        }

        $data = $response->json();

        Transfer::create([
            'user_id' => $user->id,
            'tx' => $data['tx'],
            ...$requestData,
        ]);

        return response()->json([
            'message' => 'Transfer transaction created successfully.',
            'tx' => $data['tx'],
        ], 200);
    }
}
