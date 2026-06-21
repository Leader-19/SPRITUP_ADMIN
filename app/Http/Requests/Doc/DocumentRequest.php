<?php

namespace App\Http\Requests\Doc;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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

            'doc_name' => 'required|string|max:255',
            'doc_title' => 'required|string|max:255',
            'doc_upload' => 'nullable|file|max:100000',
            'description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',

        ];
    }
}
