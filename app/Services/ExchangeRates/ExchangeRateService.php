<?php

namespace App\Services\ExchangeRates;

use Exception;

class ExchangeRateService
{
    private $end_date = null;
    private $start_date = null;
    private $base_symbol = null;
    private $api_key = "y8NIm1dmPLDfQk81rQZNH8gd8peL7v9K";
    private $base_url = "https://api.apilayer.com/exchangerates_data/timeseries";

    public function __construct($base_symbol, $start_date, $end_date)
    {
        $this->end_date = $end_date;
        $this->start_date = $start_date;
        $this->base_symbol = $base_symbol;
    }

    public function getData()
    {
        $data = $this->apiCall();

        if (isset($data['success']) && $data['success'] == true) {
            // SAVE DATA TO DATABASE
        }
    }

    public function apiCall()
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->base_url . '?base=' . $this->base_symbol . '&start_date=' . $this->start_date . '&end_date=' . $this->end_date,
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
