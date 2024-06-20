<?php

namespace App\Http\Requests\V1\Blog;

use Illuminate\Foundation\Http\FormRequest;


class UpdateBlogRequest extends FormRequest
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
        if ($method == "PUT") {
            return [
                "name"=>["required","string","max:255"],
                "email"=>["required","email","unique:users"],
                "password"=>['required'],
                "is_admin"=>["required","boolean"]
            ];
        } else {
            return [
                "name"=>["required","sometime","string","max:255"],
                "email"=>["required","sometime","email","unique:users"],
                "password"=>['required',"sometime"],
                "is_admin"=>["required","boolean"]
            ];
        }
    }
}
