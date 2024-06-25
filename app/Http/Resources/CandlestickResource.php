<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandlestickResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "open" => number_format($this->open, 6),
            "high" => number_format($this->high, 6),
            "low" => number_format($this->low, 6),
            "volume" => number_format($this->volume, 6),
            "close" => number_format($this->close, 6),
            "timestamp" => $this->timestamp,
        ];
    }
}
