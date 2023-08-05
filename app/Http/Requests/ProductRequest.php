<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:products,name|max:128',
            'description' => 'required|max:255',
            'image' => 'required',
            'image.*' => 'mimes:png,jpeg,jpg|max:1024',
            'price' => 'required|numeric|gt:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
