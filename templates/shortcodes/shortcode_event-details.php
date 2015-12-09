<?php
    global $xi_event_id, $xi_event_meta;
?>
<div class="xi_event-meta">
    <?=$xi_event_id;?>
    <pre style="font-size: 11px; line-height: 11px; max-height: 300px; overflow-y: auto;">
        <?php print_r($xi_event_meta); ?>
    </pre>
</div>
