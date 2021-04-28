<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use App\Services\ConverterService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    private $currencies;
    private $converter;

    public function __construct(Currencies $currencies, ConverterService $converter)
    {
        $this->currencies = $currencies;
        $this->converter = $converter;
    }

    public function index()
    {
        $allCurrencies = $this->currencies->pluck('combined_name')->all(); //get all currencies from DB

        $displayData = [];
        if (session()->get('redir')) {
            $amount = session()->get('amount');
            $from = session()->get('from');
            $to = session()->get('to');

            $convertedAmount = $this->converter->convertCurrency($from, $to, $amount);
            $displayData = ['convertedAmount' => $convertedAmount, 'convertedTo' => $to, 'originalAmount' => $amount, 'convertedFrom' => $from,];
        }

        return view('index', array_merge(
            [
                'currencies' => $allCurrencies,
                'mostConvertedCurr' => 'Test',
                'totalConversions' => 1,
                'totalConverted' => 2,
            ],
            $displayData
        ));
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
