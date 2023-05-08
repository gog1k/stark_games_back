<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievements;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AchievementController extends Controller
{
    public function getListAction(): Response
    {
        if (auth()->user()->isSuperUser()) {
            $response = Achievements::query();
        } else {
            $response = Achievements
                ::whereIn('project_id', auth()->user()->projectsAllowedForAdministrationIds());
        }

        $response = $response
            ->with('project')
            ->with('event')
            ->with('itemTemplate')
            ->paginate(10);

        return response([
            'items' => $response->items(),
            'pagination' => [
                'currentPage' => $response->currentPage(),
                'perPage' => $response->perPage(),
                'total' => $response->total(),
            ]
        ]);
    }

    public function getAction(int $id): Response
    {
        return response(
            Achievements::where(['id' => $id])->first()
        );
    }

    public function createAction(Request $request): Response
    {
        $request->validate([
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'project_id' => 'required|integer|exists:projects,id',
            'count' => 'required|integer|exists:projects,id',
            'item_template_id' => 'required|integer|exists:item_templates,id',
            'event_id' => 'required|integer|exists:events,id',
            'event_fields' => 'array',
        ]);

        $achievements = Achievements::create([
            'active' => $request->active,
            'name' => $request->name,
            'project_id' => $request->project_id,
            'count' => $request->count,
            'item_template_id' => $request->item_template_id,
            'event_id' => $request->event_id,
            'event_fields' => $request->event_fields,
        ]);

        return response($achievements);
    }

    public function updateAction(Request $request): Response
    {
        $request->validate([
            'id' => 'required|integer|exists:achievements,id',
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
        ]);

        $achievement = Achievements::findOrFail($request->id);

        $achievement->active = $request->active;
        $achievement->name = $request->name;

        $achievement->save();

        return response($achievement);
    }
}
