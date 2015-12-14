<?php
    global $xi_widget_args, $xi_widget_instance, $upcoming_events;
?>
<section class="widget xi_widget-upcoming-events">
    <h2 class="widget-title"><?=$xi_widget_instance['title'];?></h2>
    <?php if (!empty($upcoming_events)) : ?>
        <ul class="xi_upcoming-events-list">
            <?php foreach ($upcoming_events as $upcoming_event) : ?>
                <li>
                    <a href="<?=$upcoming_event->permalink;?>"><span class="xi_upcoming-event-list-date"><strong><?=$upcoming_event->date;?><span class="colon">:</span></strong></span> <?=$upcoming_event->title;?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <span class="xi_no-events"><strong>No upcoming events at the moment - check back soon!</strong></span>
    <?php endif; ?>
</section>
