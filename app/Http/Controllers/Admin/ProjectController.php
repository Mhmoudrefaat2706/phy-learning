<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->paginate(6);
        return view('admin.pages.projects.list', compact('projects'));
    }

   public function store(StoreProjectRequest $request)
{
    $data = $request->validated();

    $data['slug'] = Str::slug($data['title']);


    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('projects', $filename, 'public');
        $data['image'] = $path;
    }

    $project = Project::create($data);
    $project->load('category');

    return response()->json([
        'success' => true,
        'message' => 'Project created successfully.',
        'project' => $project
    ]);
}

public function update(UpdateProjectRequest $request, Project $project)
{
    $data = $request->validated();
    if (isset($data['title'])) {
        $data['slug'] = Str::slug($data['title']);
    }


    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('projects', $filename, 'public');
        $data['image'] = $path;
    }

    $project->update($data);
    $project->load('category');

    return response()->json([
        'success' => true,
        'message' => 'Project updated successfully.',
        'project' => $project
    ]);
}


    public function show(Project $project)
    {
        $project->load(['category', 'videos']);
        return view('admin.pages.projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully.'
        ]);
    }
}
