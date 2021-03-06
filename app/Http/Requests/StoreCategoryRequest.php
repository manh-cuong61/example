<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Validation\ValidationException;

use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Http\JsonResponse;

class StoreCategoryRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|unique:categories|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'bạn chưa thêm danh mục',
            'name.unique' => 'danh mục đã tồn tại',
        ];
    }

    // protected function failedValidation(Validator $validator) 
    // {

    //     $errors = (new ValidationException($validator))->errors();
    //     throw new HttpResponseException(response()->json(
    //         [
    //             'error' => $errors,
    //             'status_code' => 422,
    //         ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    // }
}
