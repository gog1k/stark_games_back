<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\File;

class ItemTemplateController extends Controller
{
    public function indexAction(): Response
    {
        return response(ItemTemplate::get());
    }

    public function listForTemplateAction($templateId): Response
    {
        return response(ItemTemplate::with('items')->whereHas('items', fn($query) => $query->where(['room_items.id' => $templateId]))->get());
    }

    public function listForItemAction($itemId): Response
    {
        return response(ItemTemplate::with('items')->whereHas('items', fn($query) => $query->where(['room_items.id' => $itemId]))->get());
    }

    public function getAction($id): Response
    {
        $template = ItemTemplate::with('items')->findOrFail($id);
        $items = $template->items->pluck('id');
        return response(array_merge($template->toArray(), ['items' => $items]));
    }

    public function createAction(Request $request): Response
    {
        $request->validate([
            'active' => 'required|boolean',
            'name' => 'required|string|max:255',
            'template' => 'required|string',
        ]);

        $roomItem = ItemTemplate::create([
            'active' => $request->active,
            'name' => $request->name,
            'template' => $request->template,
        ]);

        return response($roomItem);
    }

    public function updateAction(Request $request): Response
    {
        $request->validate([
            'id' => 'required|integer|exists:room_items,id',
            'active' => 'required',
            'name' => 'required|string|max:255',
            'file' => [
                File::image()
                    ->min(1024)
                    ->max(12 * 1024),
                'nullable'
            ]
        ]);

        $itemTemplate = ItemTemplate::findOrFail($request->id);

        if ($request->file) {
            $path = $request->file->store('templates');
            $itemTemplate->template = asset('storage/' . $path);
        }

        $itemTemplate->active = (bool)$request->active;
        $itemTemplate->name = $request->name;


        $itemTemplate->save();

        return response($itemTemplate);
    }
}
