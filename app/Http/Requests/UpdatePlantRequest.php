<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Plant;

class UpdatePlantRequest extends FormRequest
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
        $slug = $this->route('slug');

        return [
            'name'        => 'required|string|unique:plants,name,' . $slug . ',slug',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images'      => 'nullable|array',
            'images.*'    => 'nullable|url'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $slug = $this->route('slug');
            $plant = Plant::where('slug', $slug)->first();

            $existing = $plant?->images()->count() ?? 0;
            $new = is_array($this->images) ? count($this->images) : 0;

            if (($existing + $new) > 4) {
                $validator->errors()->add('images', 'Vous ne pouvez pas dépasser 4 images par plante. (actuelles : ' . $existing . ')');
            }
        });
    }
}
