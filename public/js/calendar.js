$(document).ready(function () {
    $('#calendar').fullCalendar({
        editable: false,
        header: {
            left: 'month, agendaWeek',
            center: 'title',
            right: 'prev, next, today',
        },
        dayClick: function(data, event, view) {
            $('h5').text('Add event');
            $('#EditEventModal').modal();
        },
        eventClick: function(data, event, view) {
            $('h5').text('Update event')
            $('input[name=event_id]').val(data._id);
            $('input[name=title]').val(data.title);
            $('input[name=start_time]').val(data.start._i);
            $('input[name=end_time]').val(data.end._i);
            $('input[name=bg_color]').val(data.color);
            $('input[name=text_color]').val(data.textColor);
            $('#EditEventModal').modal();
        }
    });

    $('#event_edit_submit').on('click', function () {
        var event_id = '';
        var title = $('input[name=title]').val();
        var start_time = $('input[name=start_time]').val();
        var end_time = $('input[name=end_time]').val();
        var bg_color = $('input[name=bg_color]').val();
        var text_color = $('input[name=text_color]').val();
        var edit_type = $('h5').text();
        var url ='';

        if (edit_type == 'Add event')
            url = '/event/add';
        else {
            url = '/event/update';
            event_id = $('input[name=event_id]').val()
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                event_id : event_id,
                title: title,
                start_time: start_time,
                end_time: end_time,
                bg_color: bg_color,
                text_color: text_color,
            },
            success: function (res) {
                if (edit_type == 'Update event') {
                    $('#calendar').fullCalendar('removeEvents', event_id);
                }
                $('#calendar').fullCalendar('renderEvent', {
                    _id: res.id.toString(),
                    title: title,
                    start: start_time,
                    end: end_time,
                    color: bg_color,
                    textColor: text_color,
                });
                $('#EditEventModal').modal('toggle');
            },
            error: function (res) {

                res = JSON.parse(res.responseText);
                error_span = '<span class="invalid-feedback" role="alert">meow<strong></strong></span>';
                $.each(res.errors, function (key, value) {
                    $('input[name=' + key + ']').after(error_span.replace('meow', value));
                });
                $('.invalid-feedback').show();
            }
        });
    });
});
