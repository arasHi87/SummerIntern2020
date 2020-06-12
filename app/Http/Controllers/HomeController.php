<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $results = User::find(Auth::id())->events;
        $events = array();

        foreach ($results as $result) {
            $result['notice_day'] = Redis::get("events_" . Auth::id() . ':' . $result['id']);
            array_push($events, $result);
        }

        return view('home', compact('events'));
    }
}
