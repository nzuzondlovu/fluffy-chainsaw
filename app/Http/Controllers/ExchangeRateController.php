<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use App\Http\Requests\SearchExchangeRateRequest;
use App\Services\ExchangeRates\ExchangeRateService;

class ExchangeRateController extends Controller
{
    /**
     * Load the index view
     *
     * @return View
     */
    public function index()
    {
        $symbols = Symbol::all();

        if ($symbols->isEmpty()) {
            $service = new ExchangeRateService();
            $service->getData('symbols');
        }

        return view('index', ['symbols' => $symbols]);
    }

    public function search(SearchExchangeRateRequest $request)
    {
        dd($request->all());
    }
}
