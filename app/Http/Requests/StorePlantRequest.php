<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlantRequest extends FormRequest
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
            'name'        => 'required|string|unique:plants,name',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'required|array|max:4',
            'images.*'    => 'required|url'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($this->images ?? []) > 4) {
                $validator->errors()->add('images', 'Vous ne pouvez ajouter que 4 images maximum.');
            }
        });
    }
}
