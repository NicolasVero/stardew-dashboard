<?php

/**
 * Génère le code HTML du bouton pour afficher le panneau des lieux visités.
 *
 * @return string Le code HTML du bouton pour afficher le panneau des lieux visités.
 */
function display_visited_locations_button(): string {
    $version_class = get_version_class("1.6.0");

	return "<img src='" . get_images_folder() . "/icons/location_icon.png' class='$version_class-icon visited-locations-icon view-visited-locations view-visited-locations-" . get_current_player_id() . " button-elements modal-opener icon' alt='Visited locations icon'>";
}

/**
 * Génère le code HTML du panneau des lieux visités.
 *
 * @return string Le code HTML du panneau des lieux visités.
 */
function display_visited_locations_panel(): string {
    $player_id = get_current_player_id();
    $visited_locations = get_locations_visited_data();
    $images_path = get_images_folder();
    $json_data = sanitize_json_with_version("locations_to_visit");

    $locations = "";  
    $batch_size = round(count($json_data) / 2);
    $counter = 0;
    
    foreach($json_data as $json_line_name) {
        if($counter % $batch_size === 0) {
            $locations .= "<span class='locations-batch'>";
        }

        $is_found = array_key_exists($json_line_name, $visited_locations);
        $element_class = $is_found ? "found" : "not-found";
    
        $wiki_link = get_wiki_link(get_item_id_by_name($json_line_name));
        $locations .= "
            <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                <span class='$element_class'>" . __($json_line_name) . "</span>
            </a>
        ";

        $counter++;

        if($counter % $batch_size === 0 || $counter === count($json_data)) {
            $locations .= "</span>";
        }
    }
    

    return "
        <section class='visited-locations-$player_id panel visited-locations-panel modal-window'>
            <span class='title'>
                <span>" . __("Visited Locations") . "</span>
            </span>
            <img src='$images_path/content/white_dashes.png' class='dashes' alt=''>
            <img src='$images_path/icons/exit.png' class='exit-visited-locations exit-visited-locations-$player_id exit' alt='Exit'>
            <span class='locations'>
                $locations
            </span>
        </section>
    ";
}
