<?php

namespace App\Http\Controllers;

use App\Repository\ConversionsInterface;
use App\Repository\CurrenciesInterface;
use App\Services\ConverterService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    private ConverterService $converter;
    private ConversionsInterface $conversions;
    private CurrenciesInterface $currencies;

    public function __construct(CurrenciesInterface $currencies, ConverterService $converter, ConversionsInterface $conversions)
    {
        $this->currencies = $currencies;
        $this->converter = $converter;
        $this->conversions = $conversions;
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $allCurrencies = $this->currencies->getAllCurrencies();

        //Get submitted values and pass them to template in array
        $displayData = [];
        if ($request->session()->get('redir')) {
            $amount = $request->session()->get('amount');
            $from = $request->session()->get('from');
            $to = $request->session()->get('to');

            $convertedAmount = $this->converter->convertCurrency($from, $to, (float)$amount);
            if ($convertedAmount) { //if this returns 0 and requested amount was not 0, return error
                $displayData = ['convertedAmount' => $convertedAmount, 'convertedTo' => $to, 'originalAmount' => $amount, 'convertedFrom' => $from,];
            } elseif ($amount !== '0') {
                $displayData = ['errorResult' => 'Remote API is currently unavailable. Please try again later.'];
            }
        }

        return view('index', array_merge(
            [
                'currencies' => $allCurrencies,
                'mostConvertedCurr' => $this->conversions->getMostConverted(),
                'totalConversions' => $this->conversions->getTotalConversions(),
                'totalConverted' => $this->conversions->getTotalConverted(),
            ],
            $displayData
        ));
    }

    public function convert(Request $request): \Illuminate\Http\RedirectResponse
    {
        $allCurrencies = $this->currencies->getAllCurrencies();

        $request->validate([
            'amount' => 'required|numeric|gte:0',
            'from' => ['required', Rule::in($allCurrencies)],
            'to' => ['required', Rule::in($allCurrencies)],
        ]);

        return redirect()
            ->route('index')
            ->withInput()
            ->with([
                'redir' => 'true',
                'amount' => $request->amount,
                'from' => $request->from,
                'to' => $request->to,
            ]);
    }
}
