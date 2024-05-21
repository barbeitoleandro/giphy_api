<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Schema()
 */

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    /**
    * @OA\Property(format="string", default="admin@win.investments", description="email", property="email"),
    * @OA\Property(format="string", default="xxxx", description="password", property="password"),
    */
    public function rules()
    {
        return [
            'email'=>'required',
            'password'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }
}
