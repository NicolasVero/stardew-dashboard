<?php 

function display_museum_panel(): string {
	$player_id = get_current_player_id();
    $museum_data = $GLOBALS["shared_players_data"]["museum_coords"];
    $images_path = get_images_folder();
    $column_start = 26;
    $column_end = 49;
    $column_breakpoints = [
        27,
        38
    ];

    $row_start = 5;
    $row_end = 17;
    $row_breakpoints = [
        12
    ];

    $table_structure = "";

    for($row_count = $row_start; $row_count < $row_end; $row_count++) {
        $table_structure .= "<tr>";

        for($column_count = $column_start; $column_count < $column_end; $column_count++) {
            if(in_array($row_count, $row_breakpoints) || in_array($column_count, $column_breakpoints)) {
                $table_structure .= "<td class='non-fillable-space'></td>";
                continue;
            }

            $current_col = ($column_count - $column_start) + 1;
            $current_row = ($row_count - $row_start) + 1;

            $museum_tooltip = "";
            foreach($museum_data as $piece_index => $piece_details) {
                if($piece_details["coords"]["X"] === $column_count && $piece_details["coords"]["Y"] === $row_count) {
                    $piece_name = ucfirst(get_item_name_by_id($piece_details["id"]));
                    $piece_filename = formate_text_for_file($piece_name);
                    $piece_type = $piece_details["type"];
                    $museum_tooltip = "
                        <span class='museum-tooltip tooltip'>
                            <img src='$images_path/$piece_type/$piece_filename.png' class='museum-piece' alt='$piece_name'/>
                            <span>$piece_name</span>
                        </span>
                    ";

                    unset($museum_data[$piece_index]);
                }
            }

            $table_structure .= "
                <td class='fillable-space col{$current_col} row{$current_row}'>
                    $museum_tooltip
                </td>
            ";
        }

        $table_structure .= "</tr>";
    }

    return "
        <section class='museum-$player_id panel museum-panel modal-window'>
            <span class='museum-block'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-museum-$player_id' alt='Exit'/>
                <table>
                    <tbody>
                        $table_structure
                    </tbody>
                </table>
            </span>
        </section>
    ";
}
