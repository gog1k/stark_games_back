<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomItemController extends Controller
{
    public function indexAction(): Response
    {
        if (auth()->user()->isSuperUser()) {
            $items = RoomItem::get();
        } else {
            $items = RoomItem
                ::whereIn('project_id', auth()->user()->projectsAllowedForAdministrationIds())
                ->get();
        }

        return response($items);
    }

    public function createAction(Request $request): Response
    {
        $request->validate([
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $roomItem = RoomItem::create([
            'active' => $request->active,
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return response($roomItem);
    }

    public function updateAction(Request $request): Response
    {
        $request->validate([
            'id' => 'required|integer|exists:room_items,id',
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $roomItem = RoomItem::findOrFail($request->id);

        $roomItem->active = $request->active;
        $roomItem->name = $request->name;
        $roomItem->type = $request->type;

        $roomItem->save();

        return response($roomItem);
    }
}
