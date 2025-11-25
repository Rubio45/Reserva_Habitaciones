<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatePlanPriceRequest extends FormRequest
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
            'rate_plan_id' => ['required', 'integer', 'exists:rate_plans,id'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
            'occupancy' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'extra_adult' => ['nullable', 'numeric', 'min:0'],
            'extra_child' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }

    /**
     * Nota adicional: Para evitar solapamientos de rangos de fechas para la misma combinación
     * (rate_plan_id, room_type_id, occupancy), podrías agregar una validación personalizada
     * que verifique que no existan registros con rangos que se solapen.
     * 
     * Ejemplo de lógica:
     * - Verificar que no exista otro registro con:
     *   - Mismo rate_plan_id, room_type_id, occupancy
     *   - Y que (date_from <= nuevo_date_to AND date_to >= nuevo_date_from)
     */
}

