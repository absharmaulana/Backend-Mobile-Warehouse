<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Survey\StoreSurveyRequest;
use App\Http\Requests\Api\Survey\UpdateSurveyRequest;
use App\Models\Survey;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurveyController
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $search = $request->query('search', '');
        $projectId = $request->query('project_id');

        $query = Survey::query();

        if ($search) {
            $query->where('title', 'ilike', "%{$search}%")
                ->orWhere('description', 'ilike', "%{$search}%");
        }

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $surveys = $query->with(['project:id,name', 'creator:id,name,email'])
            ->paginate($perPage);

        return $this->successResponse($surveys);
    }

    public function store(StoreSurveyRequest $request): JsonResponse
    {
        $survey = Survey::create([
            'project_id' => $request->input('project_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'created_by' => $request->user()->id,
            'status' => $request->input('status', Survey::STATUS_DRAFT),
            'published_date' => $request->input('published_date'),
            'notes' => $request->input('notes'),
        ]);

        return $this->successResponse($survey->load(['project', 'creator']), 'Survey created successfully', 201);
    }

    public function show(Survey $survey): JsonResponse
    {
        $survey->load(['project:id,name', 'creator:id,name,email']);

        return $this->successResponse($survey);
    }

    public function update(UpdateSurveyRequest $request, Survey $survey): JsonResponse
    {
        $survey->update($request->validated());

        return $this->successResponse($survey->load(['project', 'creator']), 'Survey updated successfully');
    }

    public function destroy(Survey $survey): JsonResponse
    {
        $survey->delete();

        return $this->successResponse(null, 'Survey deleted successfully');
    }
}
