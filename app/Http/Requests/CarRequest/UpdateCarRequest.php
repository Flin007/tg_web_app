<?php

declare(strict_types=1);

namespace App\Http\Requests\CarRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'request_id' => 'required|integer|exists:car_requests,id',
            'data' => ['required', 'string', function ($attribute, $value, $fail) {
                if (json_decode($value, true) === null && json_last_error() !== JSON_ERROR_NONE) {
                    $fail('The '.$attribute.' must be a valid JSON string.');
                }
            }],
            'finished' => 'boolean|required',
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
            'string' => 'Поле :attribute должно быть строкой',
            'boolean' => 'Поле :attribute должно быть логическим',
        ];
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return (int)$this->input('request_id');
    }

    /**
     * @return string
     */
    public function getRequestData(): string
    {
        return (string)$this->input('data');
    }

    /**
     * @return bool
     */
    public function getFinished(): bool
    {
        return (bool)$this->input('finished');
    }
}
