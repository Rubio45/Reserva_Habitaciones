<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'code' => $this->code,
            'status' => $this->status,
            'channel' => $this->channel,
            'check_in' => $this->check_in->format('Y-m-d'),
            'check_out' => $this->check_out->format('Y-m-d'),
            'adults' => $this->adults,
            'children' => $this->children,
            'currency' => $this->currency,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'notes' => $this->notes,
            'guest' => $this->whenLoaded('guest', function () {
                return [
                    'id' => $this->guest->id,
                    'first_name' => $this->guest->first_name,
                    'last_name' => $this->guest->last_name,
                    'full_name' => $this->guest->full_name,
                    'email' => $this->guest->email,
                ];
            }),
            'rooms' => ReservationRoomResource::collection($this->whenLoaded('rooms')),
        ];
    }
}

