<?php
    global $xi_calendar_id;
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $("#xi-fullcalendar").fullCalendar({
        eventSources: [
            {
                url: "<?=admin_url( 'admin-ajax.php' )?>",
                type: 'POST',
                data: {
                    action: 'events_json',
                    calendar_id: <?=$xi_calendar_id?>
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Xi Error when fetching events.');
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            }
        ]
    });
});
</script>
<div id="xi-fullcalendar"></div>
