<?php

namespace App\Http\Controllers;

use App\Models\ExchangeTransaction;
use App\Http\Requests\StoreExchangeTransactionRequest;
use App\Http\Resources\CandlestickResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ExchangeTransaction::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExchangeTransactionRequest $request): JsonResponse
    {
        $user = $request->user();
        ExchangeTransaction::create([
            ...$request->validated(),
            'user_id' => $user->id,
            'price' => $request->get('amount_to') / $request->get('amount_from')
        ]);

        return response()->json([
            'message' => 'Exchange transaction created successfully.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExchangeTransaction $exchangeTransaction): ExchangeTransaction
    {
        return $exchangeTransaction;
    }

    public function candlesticks(Request $request)
    {
        $request->validate([
            'pair' => 'required|array|size:2',
            'pair.*' => 'required|string',
            'interval' => 'required|integer',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'limit' => 'sometimes|integer|max:1000',
        ]);

        $interval = $request->get('interval');
        $pair = $request->get('pair');

        $price = "CASE WHEN token_from = '$pair[0]' THEN price ELSE 1 / price END";

        $exchangeTransactions = ExchangeTransaction::query()
            ->whereIn('token_from', $pair)
            ->whereIn('token_to', $pair)
            ->when($request->has(['start_time', 'end_time']), function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    date('Y-m-d H:i:s', $request->get('start_time')),
                    date('Y-m-d H:i:s', $request->get('end_time')),
                ]);
            })
            ->selectRaw("SUBSTRING_INDEX(GROUP_CONCAT($price ORDER BY created_at ASC), ',', 1) AS open")
            ->selectRaw("MAX($price) AS high, MIN($price) AS low, SUM($price) AS volume")
            ->selectRaw("SUBSTRING_INDEX(GROUP_CONCAT($price ORDER BY created_at DESC), ',', 1) AS close")
            ->selectRaw("(UNIX_TIMESTAMP(`created_at`) DIV ($interval) ) * ($interval) AS timestamp")
            ->groupBy('timestamp')
            ->orderBy('timestamp', 'DESC')
            ->limit($request->get('limit') ?? 500)
            ->get();

        return CandlestickResource::collection($exchangeTransactions);
    }
}
