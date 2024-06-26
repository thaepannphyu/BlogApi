<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\Console\Input\Input;

class UpdateUserToAdminRequest extends FormRequest
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
                "is_admin"=>["boolean","required"]
            ];
       

    }
    protected function prepareForValidation()
    {
        dd($this->input('is_admin'));
       
        $this->merge([
            'is_admin' => $this->transformBoolean($this->input('is_admin'))
        ]);
       
    }

    private function transformBoolean($value)
    {
        if ($value === 'true') {
            return 1;
        } elseif ($value === 'false') {
            return 0;
        }

        return $value;
    }
}
