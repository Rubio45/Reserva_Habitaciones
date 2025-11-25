<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationRoomResource extends JsonResource
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
            'room_type' => $this->whenLoaded('roomType', function () {
                return [
                    'id' => $this->roomType->id,
                    'code' => $this->roomType->code,
                    'name' => $this->roomType->name,
                ];
            }),
            'room' => $this->whenLoaded('room', function () {
                return $this->room ? [
                    'id' => $this->room->id,
                    'room_number' => $this->room->room_number,
                ] : null;
            }),
            'rate_plan' => $this->whenLoaded('ratePlan', function () {
                return [
                    'id' => $this->ratePlan->id,
                    'code' => $this->ratePlan->code,
                    'name' => $this->ratePlan->name,
                ];
            }),
            'nightly_price' => $this->nightly_price,
            'date_from' => $this->date_from->format('Y-m-d'),
            'date_to' => $this->date_to->format('Y-m-d'),
        ];
    }
}

