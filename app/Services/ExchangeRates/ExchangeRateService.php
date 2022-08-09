<?php

namespace App\Services\ExchangeRates;

use Exception;

class ExchangeRateService
{
    /**
     * Query end date
     *
     * @var string
     */
    private $end_date = null;

    /**
     * Query start date
     *
     * @var string
     */
    private $start_date = null;

    /**
     * Query base symbol
     *
     * @var string
     */
    private $base_symbol = null;

    /**
     * API auth Key
     *
     * @var string
     */
    private $api_key = "y8NIm1dmPLDfQk81rQZNH8gd8peL7v9K";

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
    public function __construct($base_symbol, $start_date, $end_date)
    {
        $this->end_date = $end_date;
        $this->start_date = $start_date;
        $this->base_symbol = $base_symbol;
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
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->base_url . $endpoint . '?base=' . $this->base_symbol . '&start_date=' . $this->start_date . '&end_date=' . $this->end_date,
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
