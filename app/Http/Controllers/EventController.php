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

    private function dateConvert($notice_day_type, $now_date)
    {
        switch ($notice_day_type) {
            case 1:
                $count = 0;
                break;
            case 2:
                $count = 1;
                break;
            case 3:
                $count = 2;
                break;
            case 4:
                $count = 3;
                break;
            case 5:
                $count = 7;
                break;
            case 6:
                $count = 14;
                break;
            case 7:
                $count = 21;
                break;
            case 8:
                $count = 31;
                break;
        }

        return date('Y-m-d', strtotime("$now_date -$count day"));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:50'],
            'end_time' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time'],
            'start_time' => ['required', 'date_format:Y-m-d'],
            'notice_day_type' => ['nullable', 'int', 'min:0', 'max:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        // get val
        $user_id = Auth::id();
        $title = $request->input('title');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $text_color = $request->input('text_color');
        $bg_color = $request->input('bg_color');
        $notice_day_type = (int)$request->input('notice_day_type');

        // insert val
        $event = new Event;
        $event->user_id = $user_id;
        $event->title = $title;
        $event->start_time = $start_time;
        $event->end_time = $end_time;
        $event->text_color = $text_color ? $text_color : '#FFFFFF';
        $event->bg_color = $bg_color ? $bg_color : '#F44336';
        $event->notice_day_type = $notice_day_type;
        $event->save();

        // insert notice day to redis queue
        if ($notice_day_type) {
            $notice_day = $this->dateConvert($notice_day_type, $start_time);

            if (date('Y-m-d') <= $notice_day) {
                Redis::set('events_' . Auth::id() . ':' . $event->id, $notice_day);
            }
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
            'notice_day_type' => ['nullable', 'int', 'min:0', 'max:8'],
        ]);

        if ($validator->fails()) {
            return Response::json(array(
                'errors' => $validator->getMessageBag()->toArray(),
            ), 400);
        }

        // get val
        $event_id = (int)$request->input('event_id');
        $user_id = Auth::id();
        $title = $request->input('title');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $text_color = $request->input('text_color');
        $bg_color = $request->input('bg_color');
        $notice_day_type = (int) $request->input('notice_day_type');

        // update val
        Event::where('id', $event_id)
            ->where('user_id', $user_id)
            ->update([
                'title' => $title,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'text_color' => $text_color ? $text_color : '#F44336',
                'bg_color' => $bg_color ? $bg_color : '#FFFFFF',
                'notice_day_type' => $notice_day_type,
            ]);

        // insert notice day to redis queue
        if ($notice_day_type) {
            $notice_day = $this->dateConvert($notice_day_type, $start_time);

            if (date('Y-m-d') <= $notice_day) {
                Redis::set('events_' . Auth::id() . ':' . $event_id, $notice_day);
            }
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
