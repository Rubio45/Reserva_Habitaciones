<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatePlanResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'cancellation_policy' => $this->cancellation_policy,
            'meal_plan' => $this->meal_plan,
            'is_refundable' => $this->is_refundable,
            'is_active' => $this->is_active,
        ];
    }
}

