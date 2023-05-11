<?php

namespace App\Http\Controllers\Api;

use App\Events\EventCreate;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUser;
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

        $fieldsHash = hash('sha256', json_encode($fields));

        $user = User::where([
            'email' => $request->email,
        ])->first();

        $eventWithUser = $event->with('eventUsers')
            ->whereHas('eventUsers', fn($query) => $query->where([
                'user_id' => $user->id,
                'fields_hash' => $fieldsHash,
            ]))
            ->first();

        if (empty($eventWithUser) ) {
            $eventUser = EventUser::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'count' => 1,
                'fields' => $fields,
                'fields_hash' => $fieldsHash,
            ]);
        } else {
            $eventUser = $eventWithUser->eventUsers()->where(['fields_hash' => $fieldsHash,])->first();
            $eventUser->count++;
            $eventUser->save();
            $eventUser->refresh();
        }

        event(new EventCreate($eventUser));

        return response([]);
    }
}
