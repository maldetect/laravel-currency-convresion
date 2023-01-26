<?php

namespace App\Services\CurrencyLayer\Entities;

use JsonSerializable;

class CurrencyChange implements JsonSerializable {

    private ?bool $success;
    private ?string $start_date;
    private ?string $end_date;
    private ?array $quotes;
    private ?string $source;


    public function __construct($data)
    {
        $this->success = isset($data['success']) ? $data['success'] : null;
        $this->start_date = isset($data['start_date']) ? $data['start_date'] : null;
        $this->quotes = isset($data['quotes']) ? $data['quotes'] : null;
        $this->source = isset($data['source']) ? $data['source'] : null;
        $this->end_date = isset($data['end_date']) ? $data['end_date'] : null;
    }

    public function getSuccess(){
        return $this->success;
    }

    public function getStartDate(){
        return $this->start_date;
    }

    public function getQuotes(){
        return $this->quotes;
    }

    public function getSource(){
        return $this->source;
    }

    public function getEndDate(){
        return $this->end_date;
    }

    public function toArray(){
        return [
            'success' => $this->success,
            'start_date' => $this->start_date,
            'quotes' => $this->quotes,
            'source' => $this->source,
            'end_date' => $this->end_date,

        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
