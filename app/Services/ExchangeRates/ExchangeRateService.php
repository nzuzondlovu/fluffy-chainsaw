<?php

namespace App\Services\ExchangeRates;

use Exception;
use App\Models\Symbol;
use App\Models\ExchangeRate;

class ExchangeRateService
{
    /**
     * Query end date
     *
     * @var string
     */
    private $end_date = '';

    /**
     * Query start date
     *
     * @var string
     */
    private $start_date = '';

    /**
     * Query base symbol
     *
     * @var string
     */
    private $base_symbol = '';

    /**
     * API auth Key
     *
     * @var string
     */
    private $api_key = '';

    /**
     * API base url
     *
     * @var string
     */
    private $base_url = "https://api.apilayer.com/exchangerates_data/";

    /**
     * Instantiate service construct function
     *
     * @param string $base_symbol
     * @param string $start_date
     * @param string $end_date
     */
    public function __construct($base_symbol = '', $start_date = '', $end_date = '')
    {
        $this->end_date = $end_date;
        $this->start_date = $start_date;
        $this->base_symbol = $base_symbol;
        $this->api_key = env('EXCHANGE_RATE_KEY');
    }

    /**
     * Get data and save it on system
     *
     * @param string $endpoint
     * @return array
     */
    public function getData($endpoint = 'timeseries')
    {
        $data = $this->apiCall($endpoint);

        if (isset($data['success']) && $data['success'] == true) {
            // SAVE DATA TO DATABASE
            if ($endpoint == 'timeseries') {
                $rates = $data['rates'];

                foreach ($rates as $key => $value) {
                    $symbol = Symbol::where('code', $data['base'])->first();

                    foreach ($value as $code => $rate) {
                        ExchangeRate::updateOrCreate([
                            'rate_date' => $key,
                            'symbol_code' => $code,
                            'symbol_id' => $symbol->id,
                        ], [
                            'rate_value' => $rate
                        ]);
                    }
                }
            } else {
                foreach ($data['symbols'] as $key => $value) {
                    Symbol::updateOrCreate([
                        'code' => $key
                    ], [
                        'name' => $value
                    ]);
                }
            }
        }
    }

    /**
     * Call the api for data
     *
     * @param string $endpoint
     * @return array
     */
    public function apiCall($endpoint)
    {
        try {
            $query = 'symbols';

            if ($endpoint != $query) {
                $query = $endpoint . '?base=' . $this->base_symbol . '&start_date=' . $this->start_date . '&end_date=' . $this->end_date;
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->base_url . $query,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'apikey: ' . $this->api_key
                ),
            ));

            $response = curl_exec($curl);
            $response = json_decode($response, true);
            curl_close($curl);

            return $response;
        } catch (Exception $e) {
            report($e);
        }
    }
}
