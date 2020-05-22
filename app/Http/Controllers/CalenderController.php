<?php

namespace App\Http\Controllers;

use App\Calender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CalenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'calender_title' => ['required', 'max:50'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);
        
        $Calender = new Calender;
        $Calender->calender_title = $request->input('calender_title');
        $Calender->start_date = $request->input('start_date');
        $Calender->end_date = $request->input('end_date');
        $Calender->user_id = Auth::id();
        $Calender->save();
        return 'success';
    }
}
