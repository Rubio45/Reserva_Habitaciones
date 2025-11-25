<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRatePlanPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rate_plan_id' => ['sometimes', 'required', 'integer', 'exists:rate_plans,id'],
            'room_type_id' => ['sometimes', 'required', 'integer', 'exists:room_types,id'],
            'date_from' => ['sometimes', 'required', 'date'],
            'date_to' => ['sometimes', 'required', 'date', 'after_or_equal:date_from'],
            'occupancy' => ['sometimes', 'required', 'integer', 'min:1'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'extra_adult' => ['nullable', 'numeric', 'min:0'],
            'extra_child' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }
}

