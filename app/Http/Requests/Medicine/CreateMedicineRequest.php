<?php

namespace App\Http\Requests\Medicine;

use App\Enums\Media\IsMain;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class CreateMedicineRequest extends FormRequest
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
        'name' => 'required|string|max:255',
        'genericName' => 'required|string|max:255',
        'facturer' => 'required|string|max:255',
        'dosageForm' => 'required|string|max:100',
        'strength' => 'required|numeric|min:1',
        'isLimited' => 'required|boolean',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
        'expiredAt' => 'required|date|after:today',
        'description' => 'nullable|string|max:1000',
        'categoryId' => 'required|exists:categories,id',
        'productMedia' => 'nullable|array',
        'productMedia.*.path' => 'required|image',
        'productMedia.*.isMain' => ['required',new Enum(IsMain::class)],
        ];
    }
}
