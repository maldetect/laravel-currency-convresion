<?php

namespace App\Services\CurrencyLayer\Entities;

use JsonSerializable;

class CurrencyList implements JsonSerializable {

    private ?bool $success;
    private ?array $currencies;

    public function __construct($data)
    {
        $this->success = isset($data['success']) ? $data['success'] : null;
        $this->currencies = isset($data['currencies']) ? $data['currencies'] : null;
    }

    public function getSuccess(){
        return $this->success;
    }

    public function getCurrencies(){
        return $this->currencies;
    }
    public function toArray(){
        return [
            'success' => $this->success,
            'currencies' => $this->currencies
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
