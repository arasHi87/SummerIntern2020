<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calender extends Model
{
    protected $fillable = [
        'calender_title', 'start_date', 'end_date', 'user_id',
    ];
}
