<?php
    // Template to render out the event details shortcode. Used by the core plugin for the single event view.
    global $xi_event_id, $xi_event_meta;
?>
<div class="xi_event-meta">
    <?php /* ?>
    If you want to see the contents of the available event meta, display this:
    <pre style="font-size: 11px; line-height: 11px; max-height: 300px; overflow-y: auto;">
        <?php print_r($xi_event_meta); ?>
    </pre>
    */ ?>
    <h2>Event Details</h2>
    <p>
        <strong>Time:</strong> <?=XiEventmeta::render_event_time($xi_event_id, $xi_event_meta);?>
        <?php
            $categories = wp_get_post_terms($xi_event_id, XiEvents::$category_taxonomy_name);
            if (!empty($categories)) : ?>
                <br /><strong><?=_n('Category', 'Categories', count($categories));?>:</strong>
                <?php foreach ($categories as $category) : ?>
                    <a href="<?=get_term_link($category);?>"><?=$category->name;?></a>
                <?php endforeach; ?>
            <?php endif;
        ?>
    </p>
    <?php if (!empty($xi_event_meta['xi_event_venue_formatted_address'])) : ?>
        <h2>Venue Details</h2>
        <p>
            <strong><?=$xi_event_meta['xi_event_venue_name'];?></strong><br />
            <?=$xi_event_meta['xi_event_venue_formatted_address'];?>
            <?php if ($xi_event_meta['xi_event_venue_google_map'] == "link") : ?>
                <br /><a href="https://www.google.com/maps/?q=<?=urlencode($xi_event_meta['xi_event_venue_formatted_address']);?>" target="_blank">Show on map</a>
            <?php endif; ?>
        </p>
        <?php if ($xi_event_meta['xi_event_venue_google_map'] == "yes") : ?>
            <p>
                <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD8EQ7EtpgA7ZzM37_CroEjuMX__vEf-NM&q=<?=urlencode($xi_event_meta['xi_event_venue_formatted_address']);?>" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Event Information</h2>
</div>
