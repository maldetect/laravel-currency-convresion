<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Throwable;

class ApiErrorResponse  implements Responsable
{
    public function __construct(
        protected ?Throwable $e,
        protected string $message,
        protected int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        protected array $headers = []
    )
    {}

    public function toResponse($request)
    {
        $response = ['message' => $this->message];

        if ($this->e && config('app.debug')){
            $response['degub'] = [
                'message' => optional($this->e)->getMessage(),
                'file' => optional($this->e)->getFile(),
                'line' => optional($this->e)->getLine(),
                'trace' => optional($this->e)->getTraceAsString(),
            ] ;
        }

        return response()->json($response, $this->code, $this->headers);
    }
}
