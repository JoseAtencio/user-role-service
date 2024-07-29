<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Models\ConversionHistory;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class CurrencyConverterController extends Controller
{
    public function convert(Request $request)
    {
        $request->validate([
            'from_currency' => 'required|string',
            'to_currency' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $fromCurrency = $request->input('from_currency');
        $toCurrency = $request->input('to_currency');
        $amount = $request->input('amount');
        $conversion = ConversionHistory::where('from_currency', $fromCurrency)
            ->where('to_currency', $toCurrency)
            ->where('amount', $amount)
            ->first();

        if ($conversion) {
            return response()->json($conversion);
        }
        $apiKey = env('EXCHANGE_RATE_API_KEY');
        $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/pair/{$fromCurrency}/{$toCurrency}/{$amount}");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch conversion rate'], 500);
        }

        $convertedAmount = $response->json()['conversion_result'];
        $conversion = ConversionHistory::create([
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'amount' => $amount,
            'converted_amount' => $convertedAmount,
        ]);
        return response()->json($conversion);
    }
}