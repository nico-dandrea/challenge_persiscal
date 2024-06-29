<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'customer_name' => 'sometimes|string|max:255',
            'tour_name' => 'sometimes|string|max:255',
            'hotel_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|string|in:booking_date,start_date,end_date',
            'from' => 'sometimes|date|before_or_equal:to',
            'to' => 'sometimes|date|after_or_equal:from',
            'order_by' => 'sometimes|string|in:customer_name,tour_name,hotel_name,booking_date,start_date,end_date',
            'order_direction' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.in' => 'The date field must be one of: booking_date, start_date, end_date.',
            'from.before_or_equal' => 'The from date must be a date before or equal to the to date.',
            'to.after_or_equal' => 'The to date must be a date after or equal to the from date.',
            'order_by.in' => 'The order_by field must be one of: customer_name, tour_name, hotel_name, booking_date, start_date, end_date.',
            'order_direction.in' => 'The order_direction field must be one of: asc, desc.',
            'page.integer' => 'The page field must be an integer.',
            'page.min' => 'The page field must be at least 1.',
            'per_page.integer' => 'The per_page field must be an integer.',
            'per_page.min' => 'The per_page field must be at least 1.',
            'per_page.max' => 'The per_page field may not be greater than 100.',
        ];
    }
}
