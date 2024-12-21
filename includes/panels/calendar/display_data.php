<?php 

function display_calendar_panel(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $season = get_player_season();
    $all_dates = $GLOBALS["json"]["all_dates"];
    $villagers = sanitize_json_with_version("villagers");
    $week_count = 4;
    $day_count = 7;

    $table_structure = "";

    for($lines = 0; $lines < $week_count; $lines++) {
        $table_structure .= "<tr>";

        for($columns = 1; $columns <= $day_count; $columns++) {
            $day_digit = ($lines * $day_count) + $columns;
            $date = "$day_digit/$season";

            if(!array_key_exists($date, $all_dates)) {
                $table_structure .= "
                <td class='simple-event not-filled'>
                    <span></span>
                </td>";

                continue;
            }

            if(!is_array($all_dates[$date])) {
                $wiki_link = get_wiki_link(get_custom_id($all_dates[$date]));
                $calendar_tooltip = (in_array($all_dates[$date], $villagers)) ? $all_dates[$date] . "'s Birthday" : $all_dates[$date];
                $table_structure .= "
                    <td class='simple-event filled'>
                        <span class='calendar-tooltip tooltip'>
                            <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'></a>
                            <span>$calendar_tooltip</span>
                        </span>
                    </td>
                ";

                continue;
            }

            $wiki_link = [
                get_wiki_link(get_custom_id($all_dates[$date][0])),
                get_wiki_link(get_custom_id($all_dates[$date][1]))
            ];
            $calendar_tooltip = [
                (in_array($all_dates[$date][0], $villagers)) ? $all_dates[$date][0] . "'s Birthday" : $all_dates[$date][0],
                (in_array($all_dates[$date][1], $villagers)) ? $all_dates[$date][1] . "'s Birthday" : $all_dates[$date][1]
            ];

            $table_structure .= "
                <td class='double-event filled'>
                    <span class='calendar-tooltip tooltip'>
                        <a href='" . $wiki_link[0] . "' class='wiki_link' rel='noreferrer' target='_blank'></a>
                        <span class='left'>" . $calendar_tooltip[0] . "</span>
                    </span>
                    <span class='calendar-tooltip tooltip'>
                        <a href='" . $wiki_link[1] . "' class='wiki_link' rel='noreferrer' target='_blank'></a>
                        <span class='right'>" . $calendar_tooltip[1] . "</span>
                    </span>
                </td>;
            ";
            
        }

        $table_structure .= "</tr>";
    }

    return "
        <section class='calendar-$player_id panel calendar-panel modal-window'>
            <span class='calendar-block'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-calendar-$player_id' alt='Exit'/>
                <img src='$images_path/content/calendar_$season.png' class='calendar-bg' alt='Calendar background'/>
                <table>
                    <tbody>
                        $table_structure
                    </tbody>
                </table>
            </span>
        </section>
    ";
}
