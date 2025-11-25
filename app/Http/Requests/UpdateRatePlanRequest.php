<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRatePlanRequest extends FormRequest
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
        $ratePlan = $this->route('rate-plan');

        return [
            'code' => ['required', 'string', 'max:32', Rule::unique('rate_plans', 'code')->ignore($ratePlan)],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'meal_plan' => ['required', 'string', 'in:RO,BB,HB,FB,AI'],
            'is_refundable' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}

