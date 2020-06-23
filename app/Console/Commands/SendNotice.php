<?php

namespace App\Console\Commands;

use Mail;
use App\User;
use App\Event;
use App\Mail\MakeNotice;
use Illuminate\Console\Command;

class SendNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event notice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notice_list = Event::select('title', 'user_id', 'start_time', 'end_time')
                            ->where('notice_day', date('Y-m-d'))
                            ->get();
        $send_list = array();

        foreach ($notice_list as $target) {
            $user_id = $target['user_id'];
            $title = $target['title'];
            $start_time = $target['start_time'];
            $end_time = $target['end_time'];

            if (empty($send_list[$user_id])) {
                $send_list[$user_id] = array();
            }

            array_push($send_list[$user_id], array(
                'title' => $title,
                'start_time' => $start_time,
                'end_time' => $end_time,
            ));
        }

        foreach ($send_list as $user_id => $events) {
            $user = User::find($user_id);
            $user_name = $user->name;
            $user_email = $user->email;

            Mail::to($user_email)->send(new MakeNotice($events));
        }
    }
}
