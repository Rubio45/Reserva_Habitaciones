<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatePlanPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate_plan' => $this->whenLoaded('ratePlan', function () {
                return [
                    'id' => $this->ratePlan->id,
                    'code' => $this->ratePlan->code,
                    'name' => $this->ratePlan->name,
                ];
            }),
            'room_type' => $this->whenLoaded('roomType', function () {
                return [
                    'id' => $this->roomType->id,
                    'code' => $this->roomType->code,
                    'name' => $this->roomType->name,
                ];
            }),
            'date_from' => $this->date_from->format('Y-m-d'),
            'date_to' => $this->date_to->format('Y-m-d'),
            'occupancy' => $this->occupancy,
            'price' => $this->price,
            'extra_adult' => $this->extra_adult,
            'extra_child' => $this->extra_child,
            'currency' => $this->currency,
        ];
    }
}

