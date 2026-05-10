<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Project\StoreProjectRequest;
use App\Http\Requests\Api\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $search = $request->query('search', '');

        $query = Project::query();

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%")
                ->orWhere('description', 'ilike', "%{$search}%");
        }

        $projects = $query->with(['creator:id,name,email'])
            ->paginate($perPage);

        return $this->successResponse($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => $request->user()->id,
            'status' => $request->input('status', Project::STATUS_PLANNING),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'budget' => $request->input('budget'),
            'notes' => $request->input('notes'),
        ]);

        return $this->successResponse($project->load('creator'), 'Project created successfully', 201);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['creator:id,name,email', 'surveys']);

        return $this->successResponse($project);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return $this->successResponse($project->load('creator'), 'Project updated successfully');
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return $this->successResponse(null, 'Project deleted successfully');
    }
}
