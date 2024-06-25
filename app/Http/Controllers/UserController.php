<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ExchangeTransactionResource;
use App\Http\Resources\TransferResource;
use App\Http\Resources\TransferTransactionResource;
use App\Models\ExchangeTransaction;
use App\Models\Transfer;
use App\Models\TransferTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();
        $differentEmail = $request->input('email') !== $user->email;

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($differentEmail) {
            $data['email_verified_at'] = null;
        }

        if ($request->input('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        if ($differentEmail) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json($user);
    }

    public function setPassphrase(Request $request): JsonResponse
    {
        $request->validate([
            'passphrase' => 'required|string|min:16|confirmed',
        ]);

        $user = $request->user();
        $user->update(['passphrase' => $request->input('passphrase')]);

        return response()->json($user);
    }

    public function exchangeTransactions(Request $request)
    {
        $transactions = ExchangeTransaction::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($request->get('per_page', 10));

        return ExchangeTransactionResource::collection($transactions);
    }

    public function transferTransactions(Request $request)
    {
        $transactions = TransferTransaction::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($request->get('per_page', 10));

        return TransferTransactionResource::collection($transactions);
    }
    public function walletTransfers(Request $request)
    {
        $transactions = Transfer::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate($request->get('per_page', 10));

        return TransferResource::collection($transactions);
    }
}
