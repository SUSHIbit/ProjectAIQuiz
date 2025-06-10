<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx',
                'max:15360', // 15MB max (increased for PowerPoint files)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'Only PDF, DOC/DOCX, and PPT/PPTX files are allowed.',
            'file.max' => 'File size cannot exceed 15MB.',
        ];
    }
}