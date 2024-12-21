<?php 

function display_farm_animals(): string {
    $gallery_details = [
        "player_data" => get_farm_animals_data(),
        "json_filename" => "farm_animals",
        "section_title" => "Farm animals"
    ];
    $panel_details = [
        "panel_alt"     => "all-animals",
        "panel_name"    => "all farm animals"
    ];
    return display_detailled_gallery($gallery_details, "", $panel_details);
}