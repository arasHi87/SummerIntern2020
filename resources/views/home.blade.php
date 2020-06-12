@extends('layouts.app')

@section('style')
<link href="https://unpkg.com/huebee@latest/dist/huebee.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' rel='stylesheet' />
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" rel="stylesheet" />
@endsection

@section('script')
<script src="https://unpkg.com/huebee@latest/dist/huebee.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="{{ asset('js/calendar.js') }}"></script>
@endsection

@section('content')
<!-- calendar content -->
<div id="calendar" style="margin: 0 5% 3% 5%;" FullCalendar></div>

<!-- date add modal -->
<div class="modal fade" id="EditEventModal" tabindex="-1" role="dialog" aria-labelledby="EditEventModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="event_id" id="event_id" value="">
            <div class="modal-header">
                <h5 class="modal-title" id="weekday">Add event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="title_lb" class="col-md-3 col-form-label text-md-left">title</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="title" maxlength="50" placeholder='Event title'>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="start_time_lb" class="col-md-3 col-form-label text-md-left">start</label>
                    <div class="col-md-8">
                        <input type='date' class="form-control datepicker" name="start_time">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="end_time_lb" class="col-md-3 col-form-label text-md-left">end</label>
                    <div class="col-md-8">
                        <input type='date' class="form-control datepicker" name="end_time">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notice_day_lb" class="col-md-3 col-form-label text-md-left">notice</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="notice_day" placeholder="range 1-30">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="bg_color" class="col-md-3 col-form-label text-md-left">bg color</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="bg_color" value="#F44336" data-huebee>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="text_color" class="col-md-3 col-form-label text-md-left">text color</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="text_color" value="#FFFFFF" data-huebee>
                    </div>
                </div>
            </div>
             <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_event" style="display: none;">Delete</button>
                <button type="submit" class="btn btn-primary" id="event_edit_submit">Submit</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('body-script')
<script>
    notice_day_l = {};
    function waitUntil(waitFor, method) {
        if (window[waitFor]) {
            method();
        } else {
            setTimeout(() => {
                waitUntil(waitFor, method);
            }, 50);
        }
    }

    waitUntil('$', () => {
        $(document).ready(function () {
            waitUntil('FullCalendar', () => {
                @foreach($events as $event)
                    notice_day_l["{{ strval($event->id) }}"] = "{{ $event->notice_day }}";

                    $('#calendar').fullCalendar('renderEvent', {
                            _id: "{{ strval($event->id) }}",
                            title: "{{ $event->title }}",
                            start: "{{ $event->start_time }}",
                            end: "{{ $event->end_time }}",
                            color: "{{ $event->bg_color }}",
                            textColor: "{{ $event->text_color }}"
                        },
                        true,
                    );
                @endforeach
            })
        })
    })
</script>
@stop
