<?php
    global $xi_calendar_id, $xi_shortcode_attributes;
    $categories = XiQuery::get_categories_for_calendar($xi_calendar_id);
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    var category_filter_id = $('#xi-categories').val();
    var xi_source = {
        url: "<?=admin_url( 'admin-ajax.php' )?>",
        type: 'POST',
        data: {
            action: 'events_json',
            calendar_id: <?=$xi_calendar_id?>,
            category_id: category_filter_id
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Xi Error when fetching events.');
            console.log(textStatus);
            console.log(errorThrown);
        }
    };

    $("#xi-fullcalendar").fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
        eventSources: [xi_source]
    });

    $("#xi-categories").change(function() {
        category_filter_id = $(this).val();
        $('#xi-fullcalendar').fullCalendar('removeEventSource', xi_source);
        xi_source = {
            url: "<?=admin_url( 'admin-ajax.php' )?>",
            type: 'POST',
            data: {
                action: 'events_json',
                calendar_id: <?=$xi_calendar_id?>,
                category_id: category_filter_id
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Xi Error when fetching events.');
                console.log(textStatus);
                console.log(errorThrown);
            }
        };
        $('#xi-fullcalendar').fullCalendar('refetchEvents');
        $('#xi-fullcalendar').fullCalendar('addEventSource', xi_source);
        $('#xi-fullcalendar').fullCalendar('refetchEvents');
    });
});
</script>
<?php if ($xi_shortcode_attributes['show_category_filter'] == 'true' && !empty($categories)) : ?>
    <div id="xi-filter">
        <p>
            <label for="xi-categories">Filter By Category</label>
            <select name="xi-categories" id="xi-categories">
                <option value="">Show All</option>
                <?php foreach($categories as $category_id => $category_name) : ?>
                    <option value="<?=$category_id;?>"><?=$category_name;?></option>
                <?php endforeach; ?>
            </select>
        </p>
    </div>
<?php else : ?>
    <input type="hidden" name="xi-categories" id="xi-categories" value="0">
<?php endif; ?>
<div id="xi-fullcalendar"></div>
