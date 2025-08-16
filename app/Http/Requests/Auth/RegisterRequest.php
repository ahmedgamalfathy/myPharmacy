<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;
use App\Enums\User\UserTypeEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
    {//name , email , user_type , Photos , password
        return [
            'name' => 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> [
                'required',
                'min:8',
                'regex:/^.*(?=.{1,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/'
            ],
            'userType'=> ['required',new Enum( UserTypeEnum::class)],
            'photos' => ['required','array'],
            'photos.*' => ['image','mimes:jpeg,png,jpg,gif,svg','max:5000'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }

}
