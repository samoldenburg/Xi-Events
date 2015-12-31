<?php
    // Template to render out the event list shortcode.
    global $xi_shortcode_attributes, $xi_events;
?>
<div id="xi-events_list">
    <?php foreach ($xi_events as $event) :
        $event_meta = XiEventmeta::clean_meta(get_post_meta($event->ID));
        // Just a way of doing a makeshift loop
        $post = get_post($event->ID);
        setup_postdata($post);
        $excerpt = wp_trim_words(get_the_content(), 25, ' <a href=' . $event->permalink . '>...read more.</a>');
        ?>
        <div class="xi_list_event">
            <h2><a href="<?=$event->permalink;?>"><?=$event->title;?></a></h2>
            <h3><?=XiEventmeta::render_event_time($event->ID, $event_meta, $event->start_timestamp, $event->end_timestamp);?></h3>
            <p>
                <?=$excerpt;?>
            </p>
        </div>
    <?php wp_reset_postdata(); endforeach; ?>
    <div class='xi-pagination'>
        <p>
            <a href="#">pagination: todo</a>
        </p>
    </div>
</div>
