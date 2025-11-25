<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAmenityRequest extends FormRequest
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
        $amenity = $this->route('amenity');

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('amenities', 'code')->ignore($amenity)],
            'name' => ['required', 'string', 'max:120'],
        ];
    }
}

