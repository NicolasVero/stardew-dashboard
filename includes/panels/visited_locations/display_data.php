<?php 

function display_visited_locations_button(): string {
	return "<img src='" . get_images_folder() . "/icons/location_icon.png' class='visited-locations-icon view-visited-locations view-visited-locations-" . get_current_player_id() . " button-elements modal-opener icon' alt='Visited locations icon'/>";
}

function display_visited_locations_panel(): string {
    $player_id = get_current_player_id();
    $visited_locations = get_locations_visited_data();
    $images_path = get_images_folder();
    $json_data = $GLOBALS["json"]["locations_to_visit"];

    $locations = "";
    foreach($json_data as $key => $json_version) {
        foreach($json_version as $json_line_name) {
            $is_found = array_key_exists($json_line_name, $visited_locations);
            $element_class = ($is_found) ? "found" : "not-found";

            $wiki_link = get_wiki_link(get_item_id_by_name($json_line_name));
            $locations .= "
                <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                    <span class='$element_class'>$json_line_name</span>
                </a>
            ";
        }
    }

    return "
        <section class='visited-locations-$player_id panel visited-locations-panel modal-window'>
             <span class='header'>
                <span class='title'>
                    <span>Visited Locations</span>
                </span>
                <img src='$images_path/content/white_dashes.png' class='dashes' alt=''/>
                <img src='$images_path/icons/exit.png' class='exit-visited-locations exit-visited-locations-$player_id exit' alt='Exit'/>
            </span>
            <span class='locations'>
                $locations
            </span>
        </section>
    ";
}