<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    private $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    public function index()
    {
        $allCurrencies = $this->currencies->pluck('combined_name')->all(); //get all currencies from DB

        return view('index', [
            'currencies' => $allCurrencies,
            'mostConvertedCurr' => 'Test',
            'totalConversions' => 1,
            'totalConverted' => 2,
        ],);
    }

    public function convert(Request $request)
    {

        $allCurrencies = $this->currencies->pluck('combined_name')->all();

        $request->validate([
            'amount' => 'required|numeric|gte:0',
            'from' => ['required', Rule::in($allCurrencies)],
            'to' => ['required', Rule::in($allCurrencies)],
        ]);

        return redirect()->route('index')->withInput()->with(['redir' => 'true', 'amount' => $request->amount, 'from' => $request->from, 'to' => $request->to,]);
    }
}
