<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class InvoiceResource extends JsonResource
{
    // public properti
    public $status;
    public $message;

    /**
     * __construct
     * 
     * #param mixed $status
     * @param mixed $message
     * @param mixed $resource
     * @return void
     */
    #[Override]
    public function __construct($status,$message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    public function toArray(Request $request): array
    {
        return [
            'succes'    => $this->status,
            'message'   => $this->message,
            'data'      => $this->resource
        ];
    }
}
