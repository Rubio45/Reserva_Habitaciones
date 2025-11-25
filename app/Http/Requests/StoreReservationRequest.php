<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:20', 'unique:reservations,code'],
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'status' => ['nullable', 'string', 'in:PENDING,CONFIRMED,CHECKED_IN,CHECKED_OUT,CANCELLED,NO_SHOW'],
            'channel' => ['nullable', 'string', 'in:DIRECT,PHONE,WALKIN,OTA'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'rooms' => ['nullable', 'array'],
            'rooms.*.room_type_id' => ['required_with:rooms', 'integer', 'exists:room_types,id'],
            'rooms.*.room_id' => ['nullable', 'integer', 'exists:rooms,id'],
            'rooms.*.rate_plan_id' => ['required_with:rooms', 'integer', 'exists:rate_plans,id'],
            'rooms.*.nightly_price' => ['required_with:rooms', 'numeric', 'min:0'],
            'rooms.*.date_from' => ['required_with:rooms', 'date'],
            'rooms.*.date_to' => ['required_with:rooms', 'date', 'after:rooms.*.date_from'],
        ];
    }
}

