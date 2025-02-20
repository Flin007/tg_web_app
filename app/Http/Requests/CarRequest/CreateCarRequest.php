<?php

declare(strict_types=1);

namespace App\Http\Requests\CarRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'car_id' => 'required|integer',
            'user_id' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'required' => 'Обязательное поле :attribute отсутствует',
            'integer' => 'Поле :attribute должно быть числовым',
        ];
    }

    /**
     * @return int
     */
    public function getCarId(): int
    {
        return (int)$this->input('car_id');
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return (int)$this->input('user_id');
    }
}
