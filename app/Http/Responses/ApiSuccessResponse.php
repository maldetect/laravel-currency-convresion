<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class ApiSuccessResponse  implements Responsable
{
    public function __construct(
        protected mixed $data,
        protected array $metadata,
        protected int $code = Response::HTTP_OK
    )
    {}

    public function toResponse($request)
    {
        return response()->json([
            'data' => $this->data,
            'metadata' => $this->metadata
        ], $this->code);
    }
}
