<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
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
        $reservation = $this->route('reservation');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('reservations', 'code')->ignore($reservation)],
            'guest_id' => ['sometimes', 'required', 'integer', 'exists:guests,id'],
            'status' => ['sometimes', 'nullable', 'string', 'in:PENDING,CONFIRMED,CHECKED_IN,CHECKED_OUT,CANCELLED,NO_SHOW'],
            'channel' => ['sometimes', 'nullable', 'string', 'in:DIRECT,PHONE,WALKIN,OTA'],
            'check_in' => ['sometimes', 'required', 'date'],
            'check_out' => ['sometimes', 'required', 'date', 'after:check_in'],
            'adults' => ['sometimes', 'required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            // Nota: Para simplificar, en update no permitimos modificar rooms (Opción A)
            // Si quisieras permitir reemplazar rooms (Opción B), agregarías las mismas reglas que en store
        ];
    }
}

