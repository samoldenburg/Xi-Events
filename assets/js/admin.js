jQuery(document).ready(function($) {
    $(".xi_datepicker").datepicker();

    $("#event_start_date_time").change(function() {
        var date = $(this).datepicker("getDate");
        var enddate = $("#event_end_date_time").datepicker("getDate");
        if ((enddate === null && typeof enddate === "object") || date > enddate)
            $("#event_end_date_time").datepicker("setDate", date);
    });

    $("#event_all_day").change(function() {
        toggle_show_times();
    });

    function toggle_show_times() {
        if ($("#event_all_day").is(":checked")) {
            $("#event-start-time-wrapper").hide();
            $("#event-end-time-wrapper").hide();
        } else {
            $("#event-start-time-wrapper").show();
            $("#event-end-time-wrapper").show();
        }
    }
    toggle_show_times();
});
