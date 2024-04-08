<?php

namespace App\Http\Requests\V1\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $method = $this->method();
        if ($method == "PUT")
            return [
                "name" => ["required", "unique:categories"],
                "slug" => ["required", "unique:categories"],
                "description" => ["required"]
            ];
        else
            return [
                "name" => ["sometimes", "required", "unique:categories"],
                "slug" => ["sometimes", "required", "unique:categories"],
                "description" => ["sometimes", "required"]
            ];
    }
}
