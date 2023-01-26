<?php

namespace App\Services\CurrencyLayer;

use App\Contracts\CurrencyLayer\CurrencyLayerContract;
use App\Exceptions\CurrencyLayer\ExceptionHandler;
use App\Services\CurrencyLayer\Entities\CurrencyChange;
use App\Services\CurrencyLayer\Entities\CurrencyConvert;
use App\Services\CurrencyLayer\Entities\CurrencyHistorical;
use App\Services\CurrencyLayer\Entities\CurrencyList;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class CurrencyLayerService implements CurrencyLayerContract
{

    protected PendingRequest $httpClient;

    public function __construct($httpClient = null)
    {
        if (is_null($httpClient)){
            $this->httpClient = Http::currency_layer();
        }else{
            $this->httpClient = $httpClient;
        }
    }

    public function getList()
    {
        return $this->handle("list", CurrencyList::class);
    }

    public function convert(string $amount,  string $to, string $from = 'USD', ?string $date = null)
    {
        return $this->handle("/convert?amount={$amount}&from={$from}&to={$to}&date={$date}", CurrencyConvert::class);
    }

    public function getHistorical(string $date, ?string $currencies = null,  ?string $source = 'USD')
    {
        return $this->handle("/historical?date={$date}&currencies={$currencies}&source={$source}", CurrencyHistorical::class);
    }

    public function getChange(string $start_date, string $end_date, ?string $currencies = null,  ?string $source = 'USD')
    {
        return $this->handle("/change?start_date={$start_date}&end_date={$end_date}&currencies={$currencies}&source={$source}", CurrencyChange::class);
    }

    private function handle($endpoint, $class_name)
    {
        $response = $this->httpClient->get($endpoint);

        if ($response->successful()){
            if ($response->json('success')){
                return new $class_name ($response->json());
            }else{
                throw new ExceptionHandler($response->throw()->json());
            }
        }

        return $response->throw()->json();
    }
}
