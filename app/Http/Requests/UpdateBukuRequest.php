<?php

namespace App\Http\Requests;

class UpdateBukuRequest extends StoreBukuRequest
{
    // Pewarisan rule StoreBukuRequest; field yang tidak dikirim boleh dilewati.
    public function rules(): array
    {
        $rules = parent::rules();
        foreach ($rules as $field => $fieldRules) {
            $rules[$field] = array_values(array_filter($fieldRules, fn ($rule) => $rule !== 'required'));
            array_unshift($rules[$field], 'sometimes');
        }
        return $rules;
    }
}
