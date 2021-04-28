<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConversionController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index()
    {
        return view('index', [
            'currencies' => ['Test1', 'Test2'],
            'mostConvertedCurr' => 'Test',
            'totalConversions' => 1,
            'totalConverted' => 2,
        ],);
    }

    public function convert(Request $request)
    {
        //
    }
}
