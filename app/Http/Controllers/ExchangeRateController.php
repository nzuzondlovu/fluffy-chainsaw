<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use Illuminate\Http\Request;
use App\Services\ExchangeRates\ExchangeRateService;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $symbols = Symbol::all();

        if ($symbols->isEmpty()) {
            $service = new ExchangeRateService();
            $service->getData('symbols');
        }

        return view('index', ['symbols' => $symbols]);
    }
}
