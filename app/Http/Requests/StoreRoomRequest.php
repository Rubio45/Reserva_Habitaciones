<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'room_number' => ['required', 'string', 'max:32', 'unique:rooms,room_number'],
            'floor' => ['nullable', 'string', 'max:16'],
            'status' => ['nullable', 'string', 'in:AVAILABLE,OUT_OF_SERVICE,CLEANING,OCCUPIED'],
        ];
    }
}

