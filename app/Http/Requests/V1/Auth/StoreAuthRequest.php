<?php
namespace App\Http\Requests\V1\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreAuthRequest extends FormRequest{


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
            "name"=>["required","string","max:255"],
            "email"=>["required","email","unique:users"],
            "password"=>['required']
        ];
    }

}


?>