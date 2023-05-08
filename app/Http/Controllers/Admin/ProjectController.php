<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function getListAction(): Response
    {
        if (auth()->user()->isSuperUser()) {
            $response = Project
                ::withCount('users');
        } else {
            $response = Project
                ::whereIn('id', auth()->user()->projectsAllowedForAdministrationIds());
        }

        $response = $response->paginate(10);

        return response([
            'items' => $response->items(),
            'pagination' => [
                'currentPage' => $response->currentPage(),
                'perPage' => $response->perPage(),
                'total' => $response->total(),
            ]
        ]);
    }

    public function allowListAction(): Response
    {
        if (auth()->user()->isSuperUser()) {
            $response = Project::query();
        } else {
            $response = Project
                ::whereIn('id', auth()->user()->projectsAllowedForAdministrationIds());
        }

        $response = $response->get()->pluck('name', 'id');

        return response($response);
    }

    public function getAction(int $id): Response
    {
        return response(
            Project::where(['id' => $id])->first()
        );
    }

    public function createAction(Request $request): Response
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $Project = Project::create([
            'name' => $request->name,
        ]);

        return response($Project);
    }

    public function updateAction(Request $request): Response
    {
        $request->validate([
            'id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
        ]);

        $Project = Project::findOrFail($request->id);

        $Project->name = $request->name;

        $Project->save();

        return response($Project);
    }
}
