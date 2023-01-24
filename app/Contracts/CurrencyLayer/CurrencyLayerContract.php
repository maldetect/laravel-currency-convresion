<?php

namespace App\Contracts\CurrencyLayer;

interface CurrencyLayerContract
{

    public function getList();

    public function convert(string $amount, string $to , string $from = 'USD', ?string $date = null);

    public function getHistorical(string $date, ?string $currencies = null, ?string $source = null);

}
