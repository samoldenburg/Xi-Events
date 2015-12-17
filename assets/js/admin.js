jQuery(document).ready(function($) {
    $(".xi_datepicker").datepicker();
    $(".xi_colorpicker").wpColorPicker();

    // Multidates -
    // Maintain array of dates
    var exclude_dates = new Array();
    var custom_dates = new Array();

    function addExcludeDate(date) {
        if (jQuery.inArray(date, exclude_dates) < 0)
            exclude_dates.push(date);
    }

    function removeExcludeDate(index) {
        exclude_dates.splice(index, 1);
    }

    // Adds a date if we don't have it yet, else remove it
    function addOrRemoveExcludeDate(date) {
        var index = jQuery.inArray(date, exclude_dates);
        if (index >= 0)
            removeExcludeDate(index);
        else
            addExcludeDate(date);
    }

    function addCustomDate(date) {
        if (jQuery.inArray(date, exclude_dates) < 0)
            custom_dates.push(date);
    }

    function removeCustomDate(index) {
        custom_dates.splice(index, 1);
    }

    // Adds a date if we don't have it yet, else remove it
    function addOrRemoveCustomDate(date) {
        var index = jQuery.inArray(date, custom_dates);
        if (index >= 0)
            removeCustomDate(index);
        else
            addCustomDate(date);
    }

    // Takes a 1-digit number and inserts a zero before it
    function padNumber(number) {
        var ret = new String(number);
        if (ret.length == 1)
            ret = "0" + ret;
        return ret;
    }

    $("#recurrence_exceptions").datepicker({
        onSelect: function (dateText, inst) {
            addOrRemoveExcludeDate(dateText);
            $(this).data('datepicker').inline = true;
            $("#recurrence_exceptions").val(exclude_dates);
        },
        beforeShowDay: function (date) {
            var year = date.getFullYear();
            // months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
            var month = padNumber(date.getMonth() + 1);
            var day = padNumber(date.getDate());
            // This depends on the datepicker's date format
            var dateString = month + "/" + day + "/" + year;

            var gotDate = $.inArray(dateString, exclude_dates);
            if (gotDate >= 0) {
                // Enable date so it can be deselected. Set style to be highlighted
                return [true, "ui-state-highlight"];
            }
            // Dates not in the array are left enabled, but with no extra style
            return [true, ""];
        },
        onClose: function() {
            $(this).data('datepicker').inline = false;
        }
    });

    $("#custom_recurrence_dates").datepicker({
        onSelect: function (dateText, inst) {
            addOrRemoveCustomDate(dateText);
            $(this).data('datepicker').inline = true;
            $("#custom_recurrence_dates").val(custom_dates);
        },
        beforeShowDay: function (date) {
            var year = date.getFullYear();
            // months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
            var month = padNumber(date.getMonth() + 1);
            var day = padNumber(date.getDate());
            // This depends on the datepicker's date format
            var dateString = month + "/" + day + "/" + year;

            var gotDate = $.inArray(dateString, custom_dates);
            if (gotDate >= 0) {
                // Enable date so it can be deselected. Set style to be highlighted
                return [true, "ui-state-highlight"];
            }
            // Dates not in the array are left enabled, but with no extra style
            return [true, ""];
        },
        onClose: function() {
            $(this).data('datepicker').inline = false;
        }
    });

    // Toggle recurrence groups
    function show_recurrence_groups() {
        var v = $("#event_recurrence").val();
        $(".recurrence_group").hide();
        $("#recurrence_" + v).show();

        if (v != "none")
            $("#recurrence_all").show();
        else
            $("#recurrence_all").hide();

        var vm = $("#recurrence_monthly_type").val();
        if (vm == "date")
            $("#monthly_by_week").hide();
        else
            $("#monthly_by_week").show();

        var vy = $("#recurrence_yearly_type").val();
        if (vy == "date")
            $("#yearly_by_week").hide();
        else
            $("#yearly_by_week").show();        
    }
    $("#event_recurrence, #recurrence_monthly_type, #recurrence_yearly_type").change(function() {
        show_recurrence_groups();
    });
    show_recurrence_groups();

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
