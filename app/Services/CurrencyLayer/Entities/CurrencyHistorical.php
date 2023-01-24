<?php

namespace App\Services\CurrencyLayer\Entities;

use JsonSerializable;

class CurrencyHistorical implements JsonSerializable {

    private ?bool $success;
    private ?bool $historical;
    private ?array $quotes;
    private ?string $source;
    private ?string $timestamp;


    public function __construct($data)
    {
        $this->success = isset($data['success']) ? $data['success'] : null;
        $this->historical = isset($data['historical']) ? $data['historical'] : null;
        $this->quotes = isset($data['quotes']) ? $data['quotes'] : null;
        $this->source = isset($data['source']) ? $data['source'] : null;
        $this->timestamp = isset($data['timestamp']) ? $data['timestamp'] : null;
    }

    public function getSuccess(){
        return $this->success;
    }

    public function getHistorical(){
        return $this->historical;
    }

    public function getQuotes(){
        return $this->quotes;
    }

    public function getSource(){
        return $this->source;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function toArray(){
        return [
            'success' => $this->success,
            'historical' => $this->historical,
            'quotes' => $this->quotes,
            'source' => $this->source,
            'timestamp' => $this->timestamp,

        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
