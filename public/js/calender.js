$(document).ready(function () {
    $('#weekday-box').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('whatever').split(':');
        var year = recipient[0];
        var month = recipient[1];
        var day = recipient[2];
        
        $(this).find('.modal-title').text(year + ' 年 ' + month + ' 月 ' + day + '日');
    });
    
    $(function () {
        var iNow = 0;

        function run(n) {

            var oDate = new Date();
            oDate.setMonth(oDate.getMonth() + n);
            var year = oDate.getFullYear();
            var month = oDate.getMonth();
            var day = oDate.getDate();

            var allDay = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];

            if (month == 1) {
                if (year % 4 == 0 && year % 100 != 0 || year % 400 == 0) {
                    allDay = 29;
                }
            }

            oDate.setDate(1);
            var week = oDate.getDay();

            $(".weekdays").empty();

            for (var i = 0; i < week; i++) {
                $(".weekdays").append("<li></li>");
            }

            for (var i = 1; i <= allDay; i++) {
                $(".weekdays").append("<a data-toggle='modal' href='#weekday-box' data-whatever='" 
                                        + year
                                        + ":"
                                        + month
                                        + ":"
                                        + i
                                        +"'><li>" + i + "</li></a>")
            }

            $(".weekdays li").each(function (i, elm) {
                var val = $(this).text();
                if (n == 0) {
                    if (val < day) {
                        $(this).addClass('ccc')
                    } else if (val == day) {
                        $(this).addClass('red')
                    } else if (i % 7 == 0 || i % 7 == 6) {
                        $(this).addClass('sun')
                    }
                } else if (n < 0) {
                    $(this).addClass('ccc')
                } else if (i % 7 == 0 || i % 7 == 6) {
                    $(this).addClass('sun')
                }
            });

            $(".title .year_month").text(year + " 年 " + (month + 1) + " 月");
        };
        run(0);

        $("#prev").click(function () {
            iNow--;
            run(iNow);
        });

        $("#next").click(function () {
            iNow++;
            run(iNow);
        });

        $("#day").click(function () {
            run(0);
        });
    });

    $('#calender_form').on('submit', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/calender/add',
            method: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            complete: function (res) {
                $('#weekday-box').modal('toggle');    
            }
        });
        
        return false;
    });
});