<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\External;
use App\Event;
use Illuminate\Support\Facades\Date;
use Validator;
use Response;
use DateTime;

class ExternalController extends Controller
{
    public function addExternalEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'events' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        try {
            External::create([
                'name' => $request->get('name'),
                'color' => $request->get('color')
            ]);
            $external = External::query()->latest('id')->first();
            $events = $request->get('events');
            foreach($events as $event) {
                try
                {
                    $start = new DateTime($event['start']);
                    $end = null;
                    if ($event['end']) {
                        $end = new DateTime($event['end']);
                    }

                    Event::create([
                        'title' => $event['summary'],
                        'external_id' => $external['id'],
                        'type' => $event['type'],
                        'start' => $start,
                        'end' => $end,
//                        'color' => $event['description'],
                    ]);
                }
                catch (Exception $e)
                {
                    // do pass
                }
            }

//            $res_events = Event::query()->where('external_id', '=', $external['id'])->get();
            return Response::json(['success' => 'Event is Successfully created', 'external'=>$external], 200);
        } catch (Exception $e) {
            return Response::json(['error' => $e], 500);
        }
    }

    public function updateExternal(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'show' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        try {
            $external = External::find($request->get('id'));

            $external->update([
                'name' => $request->get('name'),
                'color' => $request->get('color'),
                'show' => $request->get('show')
            ]);
            $return_data = External::find($request->get('id'));
            return Response::json(['success' => 'External is Successfully updated', 'external'=>$return_data], 200);
        } catch (JWTException $e) {
            return Response::json(['error' => $e], 500);
        }
    }
}


