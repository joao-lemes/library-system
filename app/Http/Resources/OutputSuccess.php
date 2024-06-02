<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class OutputSuccess extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => true,
            'error' => false,
            'response' => $this->getMessage()
        ];
    }

    abstract protected function getMessage();
}
