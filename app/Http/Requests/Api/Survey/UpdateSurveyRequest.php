<?php

namespace App\Http\Requests\Api\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'sometimes|integer|exists:projects,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,published,closed',
            'published_date' => 'nullable|date',
            'closed_date' => 'nullable|date|after:published_date',
            'notes' => 'nullable|string',
        ];
    }
}
