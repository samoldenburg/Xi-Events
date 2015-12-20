<h4>Date &amp; Time</h4>
<table>
    <tr>
        <td>
            <label for="event_all_day">All Day Event?</label>
        </td>
        <td>
            <input type="hidden" name="event_all_day" value="0">
            <input type="checkbox" name="event_all_day" value="1" id="event_all_day"<?=XiUtilities::set_checkbox('xi_event_all_day', '1');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_start_date_time">Start Date &amp; Time:</label>
        </td>
        <td>
            <input type="text" class="xi_datepicker" name="event_start_date" id="event_start_date_time"<?=XiUtilities::set_value('xi_event_start_date_raw');?>>
            <span id="event-start-time-wrapper">
                @
                <select name="event_start_time[hours]" id="event_start_time_hours">
                    <?php for ($i = 1; $i <= 12; $i++) : $v = sprintf('%02d', $i); ?>
                        <option value="<?=$v;?>"<?=XiUtilities::set_select('xi_event_start_time_hours', $v);?>><?=$v;?></option>
                    <?php endfor; ?>
                </select>
                <select name="event_start_time[minutes]" id="event_start_time_minutes">
                    <?php for ($i = 0; $i < 60; $i++) : $v = sprintf('%02d', $i); ?>
                        <option value="<?=$v;?>"<?=XiUtilities::set_select('xi_event_start_time_minutes', $v);?>>:<?=$v;?></option>
                    <?php endfor; ?>
                </select>
                <select name="event_start_time[ampm]" id="event_start_time_ampm">
                    <option value="am"<?=XiUtilities::set_select('xi_event_start_time_ampm', 'am');?>>am</option>
                    <option value="pm"<?=XiUtilities::set_select('xi_event_start_time_ampm', 'pm');?>>pm</option>
                </select>
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_end_date_time">End Date &amp; Time:</label>
        </td>
        <td>
            <input type="text" class="xi_datepicker" name="event_end_date" id="event_end_date_time"<?=XiUtilities::set_value('xi_event_end_date_raw');?>>
            <span id="event-end-time-wrapper">
                @
                <select name="event_end_time[hours]" id="event_end_time_hours">
                    <?php for ($i = 1; $i <= 12; $i++) : $v = sprintf('%02d', $i); ?>
                        <option value="<?=$v;?>"<?=XiUtilities::set_select('xi_event_end_time_hours', $v);?>><?=$v;?></option>
                    <?php endfor; ?>
                </select>
                <select name="event_end_time[minutes]" id="event_end_time_minutes">
                    <?php for ($i = 0; $i < 60; $i++) : $v = sprintf('%02d', $i); ?>
                        <option value="<?=$v;?>"<?=XiUtilities::set_select('xi_event_end_time_minutes', $v);?>>:<?=$v;?></option>
                    <?php endfor; ?>
                </select>
                <select name="event_end_time[ampm]" id="event_end_time_ampm">
                    <option value="am"<?=XiUtilities::set_select('xi_event_end_time_ampm', 'am');?>>am</option>
                    <option value="pm"<?=XiUtilities::set_select('xi_event_end_time_ampm', 'pm');?>>pm</option>
                </select>
            </span>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_recurrence">Recurrence: </label>
        </td>
        <td>
            <select name="event_recurrence" id="event_recurrence">
                <option value="none"<?=XiUtilities::set_select('xi_event_recurrence', 'none');?>>None</option>
                <option value="daily"<?=XiUtilities::set_select('xi_event_recurrence', 'daily');?>>Daily</option>
                <option value="weekly"<?=XiUtilities::set_select('xi_event_recurrence', 'weekly');?>>Weekly</option>
                <option value="monthly"<?=XiUtilities::set_select('xi_event_recurrence', 'monthly');?>>Monthly</option>
                <option value="yearly"<?=XiUtilities::set_select('xi_event_recurrence', 'yearly');?>>Yearly</option>
                <option value="custom"<?=XiUtilities::set_select('xi_event_recurrence', 'custom');?>>Custom</option>
            </select>
        </td>
    </tr>
</table>

<div class="recurrence_group" id="recurrence_daily" style="display: none;">
    <h4>Daily Recurrence Options</h4>
</div>

<div class="recurrence_group" id="recurrence_weekly" style="display: none;">
    <h4>Weekly Recurrence Options</h4>
    <table>
        <tr>
            <td>
                <label>Day(s) of Week:</label>
            </td>
            <td>
                <table class="weekdate_select">
                    <tr>
                        <td>
                            Mon
                        </td>
                        <td>
                            Tues
                        </td>
                        <td>
                            Wed
                        </td>
                        <td>
                            Thurs
                        </td>
                        <td>
                            Fri
                        </td>
                        <td>
                            Sat
                        </td>
                        <td>
                            Sun
                        </td>
                    </tr>
                    <tr>
                        <?php
                            // This one is a little different
                            global $post;
                            $post_id = $post->ID;
                            $wrd = get_post_meta($post_id, 'xi_weekly_recurrence_days', true);
                            if (empty($wrd))
                                $wrd = array();
                            else
                                $wrd = XiUtilities::json_decode($wrd);

                        ?>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[1]" value="1"<?=(isset($wrd->{1}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[2]" value="1"<?=(isset($wrd->{2}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[3]" value="1"<?=(isset($wrd->{3}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[4]" value="1"<?=(isset($wrd->{4}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[5]" value="1"<?=(isset($wrd->{5}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[6]" value="1"<?=(isset($wrd->{6}) ? ' checked="checked"' : NULL);?>>
                        </td>
                        <td>
                            <input type="checkbox" name="weekly_recurrence_days[7]" value="1"<?=(isset($wrd->{7}) ? ' checked="checked"' : NULL);?>>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="recurrence_group" id="recurrence_monthly" style="display: none;">
    <h4>Monthly Recurrence Options</h4>
    <table>
        <tr>
            <td>
                <label for="recurrence_monthly_type">Monthly Recurrence Type:</label>
            </td>
            <td>
                <select name="recurrence_monthly_type" id="recurrence_monthly_type">
                    <option value="date"<?=XiUtilities::set_select('xi_recurrence_monthly_type', 'date');?>>Monthly By Date</option>
                    <option value="week"<?=XiUtilities::set_select('xi_recurrence_monthly_type', 'week');?>>Monthly By Day of Week</option>
                </select>
            </td>
        </tr>
        <tr id="monthly_by_week">
            <td>
                <label for="reccurence_monthly_weeknum">Select Options:</label>
            </td>
            <td>
                <select name="recurrence_monthly_weeknum" id="recurrence_monthly_weeknum">
                    <option value="first"<?=XiUtilities::set_select('xi_recurrence_monthly_weeknum', 'first');?>>First</option>
                    <option value="second"<?=XiUtilities::set_select('xi_recurrence_monthly_weeknum', 'second');?>>Second</option>
                    <option value="third"<?=XiUtilities::set_select('xi_recurrence_monthly_weeknum', 'third');?>>Third</option>
                    <option value="fourth"<?=XiUtilities::set_select('xi_recurrence_monthly_weeknum', 'fourth');?>>Fourth</option>
                    <option value="last"<?=XiUtilities::set_select('xi_recurrence_monthly_weeknum', 'last');?>>Last</option>
                </select>
                <select name="recurrence_monthly_weekday" id="recurrence_monthly_weekday">
                    <option value="Monday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Monday');?>>Monday</option>
                    <option value="Tuesday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Tuesday');?>>Tuesday</option>
                    <option value="Wednesday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Wednesday');?>>Wednesday</option>
                    <option value="Thursday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Thursday');?>>Thursday</option>
                    <option value="Friday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Friday');?>>Friday</option>
                    <option value="Saturday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Saturday');?>>Saturday</option>
                    <option value="Sunday"<?=XiUtilities::set_select('xi_recurrence_monthly_weekday', 'Sunday');?>>Sunday</option>
                </select>
                <br />
                <em>Note: The first date you chose above will be used as the first instance of this event. Subsequent events will follow this recursion rule.</em>
            </td>
        </tr>
    </table>

</div>

<div class="recurrence_group" id="recurrence_yearly" style="display: none;">
    <h4>Yearly Recurrence Options</h4>
    <table>
        <tr>
            <td>
                <label for="recurrence_yearly_type">Yearly Recurrence Type:</label>
            </td>
            <td>
                <select name="recurrence_yearly_type" id="recurrence_yearly_type">
                    <option value="date"<?=XiUtilities::set_select('xi_recurrence_yearly_type', 'date');?>>Yearly By Date</option>
                    <option value="week"<?=XiUtilities::set_select('xi_recurrence_yearly_type', 'week');?>>Yearly By Weekday in a Month</option>
                </select>
            </td>
        </tr>
        <tr id="yearly_by_week">
            <td>
                <label for="recurrence_yearly_weeknum">Select Options:</label>
            </td>
            <td>
                <select name="recurrence_yearly_weeknum" id="recurrence_yearly_weeknum">
                    <option value="first"<?=XiUtilities::set_select('xi_recurrence_yearly_weeknum', 'first');?>>First</option>
                    <option value="second"<?=XiUtilities::set_select('xi_recurrence_yearly_weeknum', 'second');?>>Second</option>
                    <option value="third"<?=XiUtilities::set_select('xi_recurrence_yearly_weeknum', 'third');?>>Third</option>
                    <option value="fourth"<?=XiUtilities::set_select('xi_recurrence_yearly_weeknum', 'fourth');?>>Fourth</option>
                    <option value="last"<?=XiUtilities::set_select('xi_recurrence_yearly_weeknum', 'last');?>>Last</option>
                </select>
                <select name="recurrence_yearly_weekday" id="recurrence_yearly_weekday">
                    <option value="Monday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Monday');?>>Monday</option>
                    <option value="Tuesday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Tuesday');?>>Tuesday</option>
                    <option value="Wednesday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Wednesday');?>>Wednesday</option>
                    <option value="Thursday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Thursday');?>>Thursday</option>
                    <option value="Friday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Friday');?>>Friday</option>
                    <option value="Saturday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Saturday');?>>Saturday</option>
                    <option value="Sunday"<?=XiUtilities::set_select('xi_recurrence_yearly_weekday', 'Sunday');?>>Sunday</option>
                </select>
                in
                <select name="recurrence_yearly_month" id="recurrence_yearly_month">
                    <option value="January"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'January');?>>January</option>
                    <option value="February"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'February');?>>February</option>
                    <option value="March"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'March');?>>March</option>
                    <option value="April"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'April');?>>April</option>
                    <option value="May"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'May');?>>May</option>
                    <option value="June"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'June');?>>June</option>
                    <option value="July"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'July');?>>July</option>
                    <option value="August"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'August');?>>August</option>
                    <option value="September"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'September');?>>September</option>
                    <option value="October"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'October');?>>October</option>
                    <option value="November"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'November');?>>November</option>
                    <option value="December"<?=XiUtilities::set_select('xi_recurrence_yearly_month', 'December');?>>December</option>
                </select>
                <br />
                <em>Note: The first date you chose above will be used as the first instance of this event. Subsequent events will follow this recursion rule.</em>
            </td>
        </tr>
    </table>
</div>

<div class="recurrence_group" id="recurrence_custom" style="display: none;">
    <h4>Specify Custom Dates</h4>
    <table>
        <tr>
            <td>
                <label for="custom_recurrence_dates">Custom Dates:</label>
            </td>
            <td>
                <input type="text" name="custom_recurrence_dates" id="custom_recurrence_dates" class="xi_datepicker_multi"<?=XiUtilities::set_value('xi_custom_recurrence_dates');?>>
            </td>
        </tr>
    </table>
</div>

<div class="recurrence_group" id="recurrence_all" style="display: none;">
    <table>
        <tr>
            <td>
                <label for="recurrence_exceptions">Exclude Days:</label>
            </td>
            <td>
                <input type="text" name="recurrence_exceptions" id="recurrence_exceptions" class="xi_datepicker_multi"<?=XiUtilities::set_value('xi_recurrence_exceptions');?>>
            </td>
        </tr>
        <tr>
            <td>
                <label for="recurrence_end">Last Day:</label>
            </td>
            <td>
                <input type="text" name="recurrence_end" id="recurrence_end" class="xi_datepicker"<?=XiUtilities::set_value('xi_recurrence_end');?>>
            </td>
        </tr>
    </table>
</div>

<h4>Venue Information</h4>
<table>
    <tr>
        <td>
            <label for="event_venue_name">Venue Name:</label>
        </td>
        <td>
            <input type="text" name="event_venue_name" id="event_venue_name"<?=XiUtilities::set_value('xi_event_venue_name');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_address_1">Address:</label>
        </td>
        <td>
            <input type="text" name="event_venue_address_1" id="event_venue_address_1"<?=XiUtilities::set_value('xi_event_venue_address_1');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_address_2">Address Line 2:</label>
        </td>
        <td>
            <input type="text" name="event_venue_address_2" id="event_venue_address_2"<?=XiUtilities::set_value('xi_event_venue_address_2');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_city">City:</label>
        </td>
        <td>
            <input type="text" name="event_venue_city" id="event_venue_city"<?=XiUtilities::set_value('xi_event_venue_city');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_state">State:</label>
        </td>
        <td>
            <input type="text" name="event_venue_state" id="event_venue_state"<?=XiUtilities::set_value('xi_event_venue_state');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_country">Country:</label>
        </td>
        <td>
            <input type="text" name="event_venue_country" id="event_venue_country"<?=XiUtilities::set_value('xi_event_venue_country');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_postal_code">Postal Code:</label>
        </td>
        <td>
            <input type="text" name="event_venue_postal_code" id="event_venue_postal_code"<?=XiUtilities::set_value('xi_event_venue_postal_code');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_phone">Phone:</label>
        </td>
        <td>
            <input type="text" name="event_venue_phone" id="event_venue_phone"<?=XiUtilities::set_value('xi_event_venue_phone');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_website">Country:</label>
        </td>
        <td>
            <input type="text" name="event_venue_website" id="event_venue_website"<?=XiUtilities::set_value('xi_event_venue_website');?>>
        </td>
    </tr>
    <tr>
        <td>
            <label for="event_venue_google_map">Show Google Map:</label>
        </td>
        <td>
            <label for="event_venue_google_map_no">
                <input type="radio" value="no" name="event_venue_google_map" id="event_venue_google_map_no"<?=XiUtilities::set_checkbox('xi_event_venue_google_map', 'no', true);?>> No Map
            </label>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td>
            <label for="event_venue_google_map_yes">
                <input type="radio" value="yes" name="event_venue_google_map" id="event_venue_google_map_yes"<?=XiUtilities::set_checkbox('xi_event_venue_google_map', 'yes');?>> Show Map
            </label>
        </td>
    </tr>
    <tr>
        <td>

        </td>
        <td>
            <label for="event_venue_google_map_link">
                <input type="radio" value="link" name="event_venue_google_map" id="event_venue_google_map_link"<?=XiUtilities::set_checkbox('xi_event_venue_google_map', 'link');?>> Show Link
            </label>
        </td>
    </tr>
</table>
