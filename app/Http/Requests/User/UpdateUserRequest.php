<?php

namespace App\Http\Requests\User;

use App\Helpers\ApiResponse;
use App\Enums\User\UserStatus;
use App\Enums\User\UserTypeEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use App\Enums\ResponseCode\HttpStatusCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required',
            'email'=> ['required',"unique:users,email,{$this->route('user')}"],
            'isActive' => ['required', new Enum(UserStatus::class)],
            'userType'=>['nullable',new Enum(UserTypeEnum::class)],
            'password'=> [
                'sometimes',
                'nullable',
                Password::min(8)->mixedCase()->numbers(),
            ],
            'roleId'=> 'required',
            'photos.*' => ['required','image'],//, "max:2048"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('', $validator->errors(), HttpStatusCode::UNPROCESSABLE_ENTITY)
        );
    }

    public function messages()
    {
        return [
            'username.unique' => __('validation.custom.username.unique'),
        ];
    }

}
