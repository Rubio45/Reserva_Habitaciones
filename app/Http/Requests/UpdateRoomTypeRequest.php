<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends FormRequest
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
        $roomType = $this->route('room-type');

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('room_types', 'code')->ignore($roomType)],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'base_occupancy' => ['required', 'integer', 'min:1'],
            'max_occupancy' => ['required', 'integer', 'min:1', 'gte:base_occupancy'],
            'bed_config' => ['nullable', 'string', 'max:120'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}

