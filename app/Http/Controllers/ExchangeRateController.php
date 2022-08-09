<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

    /**
     * Get the exchange rates for specified symbol
     *
     * @param SearchExchangeRateRequest $request
     * @return View
     */
    public function search(SearchExchangeRateRequest $request)
    {
        $symbol = Symbol::where('code', $request->base_symbol)->first();

        if (!empty($symbol)) {
            $to = Carbon::parse($request->end_date);
            $from = Carbon::parse($request->start_date);
            $dayDifference = $to->diffInDays($from) + 1;
            $results = $this->getDbRates($symbol, $from, $to);
            $ratesCount = (!$results->isEmpty()) ? $results->groupBy('rate_date')->count() : 0;

            if ($dayDifference > $ratesCount) {
                $service = new ExchangeRateService($symbol->code, $request->start_date, $request->end_date);
                $service->getData();
                $results = $this->getDbRates($symbol, $from, $to);
            }

            $min = $results->min('rate_value');
            $min = $results->where('rate_value', $min)->first();

            $avg = $results->avg('rate_value');

            $max = $results->max('rate_value');
            $max = $results->where('rate_value', $max)->first();


            return view('show', [
                'min' => $min,
                'avg' => $avg,
                'max' => $max,
                'symbol' => $symbol,
                'results' => $results
                    ->load('symbol')
                    ->groupBy('rate_date'),
                'dates' => [$request->start_date, $request->end_date]
            ]);
        }

        return redirect('/');
    }

    /**
     * Query the databse for rates
     *
     * @param Symbol $symbol
     * @param string $start_date
     * @param string $end_date
     * @return collection
     */
    public function getDbRates($symbol, $start_date, $end_date)
    {
        $results = collect([]);

        if (!empty($symbol) && !empty($start_date) && !empty($end_date)) {
            $results = $symbol
                ->exchange_rates()
                ->whereBetween('rate_date', [
                    $start_date,
                    $end_date
                ])
                ->orderBy('rate_date', 'desc')
                ->get();
        }

        return $results;
    }
}
