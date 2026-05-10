<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public properti
    public $status;
    public $message;
    public $resource;

    // constructor
    // @param mixed $status
    // @param mixed $message
    // @param mixed $resource
    // @return void

    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }
    // transform the resource into an array.
    // @param \Illuminate\Http\Request $request
    // @return array<string, mixed>

    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
