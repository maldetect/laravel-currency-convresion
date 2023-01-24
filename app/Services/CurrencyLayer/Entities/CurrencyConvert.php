<?php

namespace App\Services\CurrencyLayer\Entities;

use JsonSerializable;

class CurrencyConvert implements JsonSerializable {

    private ?bool $success;
    private ?string $date;
    private ?bool $historical;
    private ?array $info;
    private ?array $query;
    private ?float $result;

    public function __construct($data)
    {
        $this->success = isset($data['success']) ? $data['success'] : null;
        $this->date = isset($data['date']) ? $data['date'] : null;
        $this->historical = isset($data['historical']) ? $data['historical'] : null;
        $this->info = isset($data['info']) ? $data['info'] : null;
        $this->query = isset($data['query']) ? $data['query'] : null;
        $this->result = isset($data['result']) ? $data['result'] : null;

    }

    public function getSuccess(){
        return $this->success;
    }

    public function getDate(){
        return $this->date;
    }

    public function getHistorical(){
        return $this->historical;
    }

    public function getInfo(){
        return $this->info;
    }

    public function getQuery(){
        return $this->query;
    }

    public function getResult(){
        return $this->result;
    }

    public function toArray(){
        return [
            'success' => $this->success,
            'date' => $this->date,
            'historical' => $this->historical,
            'info' => $this->info,
            'query' => $this->query,
            'result' => $this->result,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
