<?php

namespace App\Exceptions\CurrencyLayer;

use App\Http\Responses\ApiErrorResponse;
use Exception;
use Illuminate\Http\Response;

class ExceptionHandler extends Exception
{
    private $data;

    public function __construct($data)
    {
        parent::__construct(
            isset($data['error']['info']) ? $data['error']['info'] : null,
            isset($data['error']['code']) ? $data['error']['code'] : Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->data = $data;
    }

    public function showMessage()
    {

        return new ApiErrorResponse(
            $this,
            isset($data['error']['info']) ? $data['error']['info'] : null,
            isset($data['error']['code']) ? $data['error']['code'] : Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
