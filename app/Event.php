<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'start_time', 'end_time', 'user_id', 'bg_color', 'text_color', 'notice_day', 'notice_day_type',
    ];
}
