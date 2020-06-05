<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:50'],
            'start_time' => ['required', 'date_format:Y-m-d'],
            'end_time' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time'],
            'notice_day' => ['int', 'min:0', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        $event = new Event;
        $event->user_id = Auth::id();
        $event->title = $request->input('title');
        $event->start_time = $request->input('start_time');
        $event->end_time = $request->input('end_time');
        $event->text_color = $request->input('text_color') ? $request->input('text_color') : '#FFFFFF';
        $event->bg_color = $request->input('bg_color') ? $request->input('bg_color') : '#F44336';
        $event->save();

        $notice_day = $request->input('notice_day');

        if ($notice_day) {
            Redis::set('events_' . Auth::id() . ':' . $event->id, $notice_day);
        }

        return response()->json(array(
            'success' => true,
            'id' => $event->id,
        ), 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'integer'],
            'title' => ['required', 'max:50'],
            'start_time' => ['required', 'date_format:Y-m-d'],
            'end_time' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time'],
            'notice_day' => ['int', 'min:0', 'max:30'],
        ]);

        if ($validator->fails()) {
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        Event::where('id', (int)$request->input('event_id'))
            ->where('user_id', Auth::id())
            ->update([
                'title' => $request->input('title'),
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'bg_color' => $request->input('bg_color') ? $request->input('bg_color') : '#FFFFFF',
                'text_color' => $request->input('text_color') ? $request->input('text_color') : '#F44336',
            ]);

        $notice_day = $request->input('notice_day');

        if ($notice_day) {
            Redis::set('events_' . Auth::id() . ':' . $request->input('event_id'), $notice_day);
        }

        return response()->json(array(
            'success' => true,
            'id' => $request->input('event_id'),
        ), 200);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        // delete event
        $event = Event::findOrFail((int)$request->input('event_id'));

        if ($event->user_id != Auth::id()) {
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        $event->delete();

        // delete redis notice
        Redis::del('events_' . Auth::id() . ':' . $request->input('event_id'));

        return response()->json(array(
            'success' => true,
            'id' => $request->input('event_id'),
        ), 200);
    }
}
