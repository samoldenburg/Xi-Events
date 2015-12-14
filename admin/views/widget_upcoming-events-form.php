<?php
    $num_events = (isset($instance['num_events'])) ? $instance['num_events'] : 3;
    $title = (!empty($instance['title'])) ? $instance['title'] : 'Upcoming Events';

?>
<p>
    <label for="<?=$this->get_field_id('title');?>">Widget Title</label>
    <input class="widefat" id="<?=$this->get_field_id('title');?>" name="<?=$this->get_field_id('title');?>" type="text" value="<?=esc_attr($title);?>">
</p>
<p>
    <label for="<?=$this->get_field_id('num_events');?>">Number of Events</label>
    <input class="widefat" id="<?=$this->get_field_id('num_events');?>" name="<?=$this->get_field_id('num_events');?>" type="number" value="<?=esc_attr($num_events);?>">
</p>
<?php foreach($taxonomies as $taxonomy => $object) :
    // This section may look a little sloppy, but it is flexible, will work with custom additional taxonomies!
    // Or any taxonomies that have been disabled or removed will be reflected here.
    $terms = get_terms($taxonomy);
    $filtered = $instance['terms'][$taxonomy]['filter'] == "1";
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#<?=$this->get_field_id('filter' . $taxonomy);?>').change(function() {
                if ($(this).is(":checked"))
                    $('#xi-upcoming-filter-<?=$this->get_field_id($taxonomy);?>').show();
                else
                    $('#xi-upcoming-filter-<?=$this->get_field_id($taxonomy);?>').hide();
            });
            if ($('#<?=$this->get_field_id('filter' . $taxonomy);?>').is(":checked"))
                $('#xi-upcoming-filter-<?=$this->get_field_id($taxonomy);?>').show();
            else
                $('#xi-upcoming-filter-<?=$this->get_field_id($taxonomy);?>').hide();
        });
    </script>
    <p>
        <input type="hidden" name="<?=$this->get_field_id('filter' . $taxonomy);?>" value="0">
        <label for="<?=$this->get_field_id('filter' . $taxonomy);?>"><input type="checkbox" id="<?=$this->get_field_id('filter' . $taxonomy);?>" name="<?=$this->get_field_id('filter' . $taxonomy);?>" value="1"<?=($filtered ? ' checked="checked"' : NULL);?>> Filter By <?=$object->labels->name;?></label>
    </p>
    <p id="xi-upcoming-filter-<?=$this->get_field_id($taxonomy);?>" style="display: none;">
        <label for="<?=$this->get_field_id($taxonomy);?>">Show Events From These <?=$object->labels->name;?></label>
        <br /><em>Use Ctrl+Click or Shift+Click to select multiple</em>
        <select class="widefat" id="<?=$this->get_field_id($taxonomy);?>" name="<?=$this->get_field_id($taxonomy);?>[]" multiple>
            <?php foreach ($terms as $term) :
                if (is_array($instance['terms'][$taxonomy])) {
                    $selected = in_array($term->term_id, $instance['terms'][$taxonomy]);
                }
                else {
                    // will be true on initialize, but preserve a complete deselection
                    $selected = !array_key_exists($taxonomy, ($instance['terms']));
                }
                ?>
                <option value="<?=$term->term_id;?>"<?=($selected ? ' selected' : NULL);?>><?=$term->name;?></option>
            <?php endforeach; ?>
        </select>
    </p>
<?php endforeach; ?>
