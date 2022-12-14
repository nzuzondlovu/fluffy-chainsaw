<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Symbol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $user = $this->getUserCookie();

        if ($symbols->isEmpty()) {
            $service = new ExchangeRateService();
            $service->getData('symbols');
            $symbols = Symbol::all();
        }

        return view('index', [
            'user' => $user,
            'symbols' => $symbols
        ]);
    }

    /**
     * Get the exchange rates for specified symbol
     *
     * @param SearchExchangeRateRequest $request
     * @return View
     */
    public function search(SearchExchangeRateRequest $request)
    {
        $user = $this->getUserCookie();
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
                'user' => $user,
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

    /**
     * Get / Set the user from cookie
     *
     * @return mixed
     */
    public function getUserCookie()
    {
        $user = null;

        if (isset($_COOKIE['user_id'])) {
            $user = User::find($_COOKIE['user_id']);
        }

        if (empty($user)) {
            $user = User::create([
                'name' => null,
                'email' => null,
                'password' => null,
            ]);

            setcookie('user_id', $user->id, time() + (86400 * 30), "/");
        }

        return $user;
    }

    /**
     * Save the exchange rate for the user
     *
     * @param SearchExchangeRateRequest $request
     * @return void
     */
    public function save(SearchExchangeRateRequest $request)
    {
        $user = $this->getUserCookie();

        if (!empty($user)) {

            DB::table('exchange_rate_user')
                ->updateOrInsert([
                    'user_id' => $user->id,
                    'end_date' => $request->end_date,
                    'start_date' => $request->start_date,
                    'symbol_code' => $request->base_symbol,

                ], []);
        }

        return redirect('/save');
    }

    /**
     * Get all the users saved exchange rates
     *
     * @param Request $request
     * @return View
     */
    public function getSaved(Request $request)
    {
        $results = collect([]);
        $user = $this->getUserCookie();

        if (!empty($user)) {
            $results = DB::table('exchange_rate_user')
                ->where('user_id', $user->id)
                ->get();
        }

        return view('save', [
            'results' => $results
        ]);
    }

    /**
     * Delete saved exchange rates
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function deleteSaved(Request $request, $id)
    {
        if (!empty($id)) {
            DB::table('exchange_rate_user')
                ->where('id', $id)
                ->delete();
        }

        return redirect('/save');
    }
}
