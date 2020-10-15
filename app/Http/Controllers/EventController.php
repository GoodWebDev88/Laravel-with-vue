<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\External;
use Illuminate\Support\Facades\Date;
use Validator;
use Response;
use DateTime;
class EventController extends Controller
{
    public function addEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        try {
            $start = new DateTime($request->get('start'));
            $end = null;
            if ($request->get('end')) {
                $end = new DateTime($request->get('end'));
            }

            Event::create([
                'title' => $request->get('title'),
                'type' => $request->get('type'),
                'start' => $start,
                'end' => $end,
                'start_time' => $request->get('start_time'),
                'end_time' => $request->get('end_time'),
                'user_id' => $request->get('user_id'),
                'project_id' => $request->get('project_id'),
                'complete' => $request->get('complete'),
                'description' => $request->get('description'),
                'multi_day' => $request->get('multi_day'),
            ]);
            $resdata = Event::query()->latest('id')->first();
            return Response::json(['success' => 'Event is Successfully created', 'event'=>$resdata], 200);
        } catch (JWTException $e) {
            return Response::json(['error' => $e], 500);
        }
    }

    public function updateEvent(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'start' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }
        $start = new DateTime($request->get('start'));
        $end = null;
        if ($request->get('end')) {
            $end = new DateTime($request->get('end'));
        }
        try {
            $event = Event::find($request->get('id'));
            $event->update([
                'title' => $request->get('title'),
                'type' => $request->get('type'),
                'start' => $start,
                'end' => $end,
                'user_id' => $request->get('user_id'),
                'project_id' => $request->get('project_id'),
                'complete' => $request->get('complete'),
                'description' => $request->get('description'),
                'multi_day' => $request->get('multi_day'),
            ]);
            $return_data = Event::find($request->get('id'));
            return Response::json(['success' => 'Event is Successfully updated', 'event'=>$return_data], 200);
        } catch (JWTException $e) {
            return Response::json(['error' => $e], 500);
        }
    }

    public function deleteEvent($id) {
        $event = Event::find($id);
        try {
            $event->delete();
            return response()->json(['success'=>'Event Successfully Removed']);
        } catch(JWTException $e) {
            return response()->json(['error'=>$e], 500);
        }
    }

    public function getEvents() {
//        $totalCount = count(Event::all());
        $events = Event::all();
        $externals = External::all();
//        if ($totalCount == 0) {
//            return response()->json(['totalCount'=>$totalCount, 'events'=>[]], 200);
//        } else {
        return response()->json(['externals'=>$externals, 'events'=>$events], 200);
//        }
    }


}
