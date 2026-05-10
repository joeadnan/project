<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public bool $status;
    public string $message;

    // ✅ Konstruktor custom tanpa #[\Override]
    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    }

    public function toArray($request): array
    {
        return [
            'status'  => $this->status,
            'message' => $this->message,
            'data'    => $this->resource,
        ];
    }
}