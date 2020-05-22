@extends('layouts.app')

<!-- Css -->
<link href="{{ asset('css/calender.css') }}" rel="stylesheet">

<!-- Script -->
<script src="{{ asset('js/calender.js') }}" defer></script>

@section('content')
<div class='container'>
    <div class="calender">
        <div class="title row">
            <button class="btn btn-success" id="today">今天</button>
            &nbsp;
            <button class="btn btn-primary" id="prev">上月</button>
            &nbsp;
            <button class="btn btn-primary" id="next">下月</button>
            &nbsp;
            <div class="year_month"></div>
        </div>
        <div class="body">
            <div class="weekday-view">
                <ui>
                    <li>日</li>
                    <li>一</li>
                    <li>二</li>
                    <li>三</li>
                    <li>四</li>
                    <li>五</li>
                    <li>六</li>
                </ui>
            </div>
            <div class='weekday-roles'>
                <ui class='weekdays'>
                </ui>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div class="modal fade" id="weekday-box" tabindex="-1" role="dialog" aria-labelledby="weekday-modal" aria-hidden="true">
    <form method="POST" action="{{ asset('js/calender.js') }}" id="calender_form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="weekday">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @csrf
                        <div class="form-group row">
                            <label for="calender-title-lb" class="col-md-2 col-form-label text-md-left">title</label>
                            <div class="col-md-8">
                                <input type='text'' class="form-control" name="calender_title" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="start_date_lb" class="col-md-2 col-form-label text-md-left">start</label>
                            <div class="col-md-8">
                                <input type='date' class="form-control datepicker" name="start_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end_date_lb" class="col-md-2 col-form-label text-md-left">end</label>
                            <div class="col-md-8">
                                <input type='date' class="form-control datepicker" name="end_date" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
@stop