<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportXmlRequest extends FormRequest
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
            'xml_file' => 'required|file|mimes:xml|max:5048',
        ];
    }

    public function messages()
    {
        return [
            'xml_file.required' => 'Please upload an XML file.',
            'xml_file.file' => 'The uploaded file must be a valid file.',
            'xml_file.mimes' => 'Only XML files are allowed.',
            'xml_file.max' => 'The XML file must not exceed 2MB.',
        ];
    }
}
