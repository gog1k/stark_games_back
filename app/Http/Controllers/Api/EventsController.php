<?php

namespace App\Http\Controllers\Api;

use App\Events\EventCreate;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;


class EventsController extends Controller
{
    public function createAction(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'code' => 'required|string|exists:events,code,project_id,' . auth()->id(),
            'fields' => 'array',
        ]);

        $event = Event::where([
            'code' => $request->code,
        ])->first();

        $fields = $event->fields;

        if (!empty($event->fields)) {
            if (empty($request->fields)) {
                throw new \Exception('fields in required');
            }

            if (!empty(
            array_diff(
                $event->fields,
                array_keys(array_filter($request->fields, fn($value) => !empty($value)))
            )
            )) {
                throw new \Exception('fields different');
            }

            $fields = [];

            foreach ($event->fields as $key) {
                $fields[$key] = $request->fields[$key];
            }
        }

        $fields = json_encode($fields);

        $user = User::where([
            'email' => $request->email,
        ])->first();

        if (empty($eventUser = $event->stats()->wherePivot('fields', $fields)->first())) {
            $event->stats()->syncWithPivotValues($user->id, ['count' => 1, 'fields' => $fields], false);
        } else {
            $event->stats()->syncWithPivotValues(
                $user->id,
                ['count' => ++$eventUser->pivot->count, 'fields' => $fields],
                false
            );
        }

        event(new EventCreate($event->id, $user->id, $fields));

        return response([]);
    }
}
