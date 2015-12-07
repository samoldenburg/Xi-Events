<h4>Date &amp; Time</h4>
<table>
    <tr>
        <td>
            <label for="event_all_day">All Day Event?</label>
        </td>
        <td>
            <input type="hidden" name="event_all_day" value="0">
            <input type="checkbox" name="event_all_day" value="1" id="event_all_day">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_start_date_time">Start Date &amp; Time:</label>
        </td>
        <td>
            <input type="text" class="xi_datepicker" name="event_start_date" id="event_start_date_time">
            @
            <select name="event_start_time[hours]" id="event_start_time_hours">
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?=$i;?>"><?=sprintf('%02d', $i);?></option>
                <?php endfor; ?>
            </select>
            <select name="event_start_time[minutes]" id="event_start_time_minutes">
                <?php for ($i = 0; $i < 60; $i++) : ?>
                    <option value="<?=$i;?>">:<?=sprintf('%02d', $i);?></option>
                <?php endfor; ?>
            </select>
            <select name="event_start_time[ampm]" id="event_start_time_ampm">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_end_date_time">End Date &amp; Time:</label>
        </td>
        <td>
            <input type="text" class="xi_datepicker" name="event_end_date" id="event_end_date_time">
            @
            <select name="event_end_time[hours]" id="event_end_time_hours">
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                    <option value="<?=$i;?>"><?=sprintf('%02d', $i);?></option>
                <?php endfor; ?>
            </select>
            <select name="event_end_time[minutes]" id="event_end_time_minutes">
                <?php for ($i = 0; $i < 60; $i++) : ?>
                    <option value="<?=$i;?>">:<?=sprintf('%02d', $i);?></option>
                <?php endfor; ?>
            </select>
            <select name="event_end_time[ampm]" id="event_end_time_ampm">
                <option value="am">am</option>
                <option value="pm">pm</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_recurrence">Recurrence: </label>
        </td>
        <td>
            <select name="event_recurrence">
                <option value="none">None</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="custom">Custom</option>
            </select>
        </td>
    </tr>
</table>

<h4>Venue Information</h4>
<table>
    <tr>
        <td>
            <label for="event_venue_name">Venue Name:</label>
        </td>
        <td>
            <input type="text" name="event_venue_name" id="event_venue_name">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_address_1">Address:</label>
        </td>
        <td>
            <input type="text" name="event_venue_address_1" id="event_venue_address_1">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_address_2">Address Line 2:</label>
        </td>
        <td>
            <input type="text" name="event_venue_address_2" id="event_venue_address_2">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_city">City:</label>
        </td>
        <td>
            <input type="text" name="event_venue_city" id="event_venue_city">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_state">State:</label>
        </td>
        <td>
            <input type="text" name="event_venue_state" id="event_venue_state">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_country">Country:</label>
        </td>
        <td>
            <input type="text" name="event_venue_country" id="event_venue_country">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_postal_code">Postal Code:</label>
        </td>
        <td>
            <input type="text" name="event_venue_postal_code" id="event_venue_postal_code">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_phone">Phone:</label>
        </td>
        <td>
            <input type="text" name="event_venue_phone" id="event_venue_phone">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_website">Country:</label>
        </td>
        <td>
            <input type="text" name="event_venue_website" id="event_venue_website">
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_google_map">Show Google Map:</label>
        </td>
        <td>
            <label for="event_venue_google_map_no">
                <input type="radio" value="no" name="event_venue_google_map" id="event_venue_google_map_no" checked="checked"> No Map
            </label>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td>
            <label for="event_venue_google_map_yes">
                <input type="radio" value="no" name="event_venue_google_map" id="event_venue_google_map_yes"> Show Map
            </label>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td>
            <label for="event_venue_google_map_link">
                <input type="radio" value="no" name="event_venue_google_map" id="event_venue_google_map_link"> Show Link
            </label>
        </td>
    </tr>
</table>
