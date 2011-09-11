<?php
/**
 * CALENDAR
 *
 * This is a partial view of the calendar widget used in the main layout.
 */

$month_of_year = date('n');                                                     // 1 - 12
$day_of_month = date('j');                                                      // 1 - 31
$days_in_this_month = date('t');                                                // 1 - 31
$day_of_week_offset = date('w', mktime(0, 0, 0, $month_of_year, 0));            // 0 - 6  (Sun - Sat)

$date_string = date('l M j, Y');
?>
                <table>
                    <tr>
                        <td id="date_string" colspan="7"><?=$date_string?></td>
                    </tr>
                    <tr>
<?php
for ($day = 0; $day < 35; $day++)
{
    $it_is_sunday = $day % 7 == 0;
    if ($it_is_sunday && $day > 0)
    {
?>
                    </tr>
                    <tr>
<?php
    }

    if ($day > $day_of_week_offset && $day - $day_of_week_offset <= $days_in_this_month)
        $this_month = true;
    else
        $this_month = false;

    $this_day = date('j', mktime(0, 0, 0, $month_of_year, $day - $day_of_week_offset));

    if ($this_day == $day_of_month)
        $today = true;
    else
        $today = false;
?>
                        <td class="<?php echo ($this_month)? 'strong' : 'faded'; echo ($today)? ' today' : ''; ?>"><?=$this_day?></td>
<?php
}
?>
                    </tr>
                </table>
<?php

/* End of file _calendar.php */
/* Location: ./application/views/_calendar.php */