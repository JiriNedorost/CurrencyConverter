<?php

namespace App\Http\Controllers;

use App\Models\Conversions;
use App\Models\Currencies;
use App\Services\ConverterService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    private $currencies;
    private $converter;
    private $conversions;

    public function __construct(Currencies $currencies, ConverterService $converter, Conversions $conversions)
    {
        $this->currencies = $currencies;
        $this->converter = $converter;
        $this->conversions = $conversions;
    }

    public function index()
    {
        $allCurrencies = $this->currencies->pluck('combined_name')->all(); //get all currencies from DB

        //Get submitted values and pass them to template in array
        $displayData = [];
        if (session()->get('redir')) {
            $amount = session()->get('amount');
            $from = session()->get('from');
            $to = session()->get('to');

            $convertedAmount = $this->converter->convertCurrency($from, $to, $amount);
            $displayData = ['convertedAmount' => $convertedAmount, 'convertedTo' => $to, 'originalAmount' => $amount, 'convertedFrom' => $from,];
        }

        //Get stats
        $mostConverted = $this->conversions->select('destination_currency')->groupBy('destination_currency')->orderByRaw('COUNT(*) DESC')->limit(1)->first()->destination_currency;
        $totalConversions = $this->conversions->count('id');
        $totalConverted = round($this->conversions->sum('amount'), 2);

        return view('index', array_merge(
            [
                'currencies' => $allCurrencies,
                'mostConvertedCurr' => $mostConverted,
                'totalConversions' => $totalConversions,
                'totalConverted' => $totalConverted,
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
