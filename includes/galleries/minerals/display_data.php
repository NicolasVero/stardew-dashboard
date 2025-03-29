<?php

/**
 * Génère le code HTML de la galerie des minéraux.
 * 
 * @return string Le code HTML de la galerie des minéraux.
 */
function display_minerals(): string {
    $gallery_details = [
        "player_data" => get_minerals_data(),
        "json_filename" => "minerals",
        "section_title" => "Minerals"
    ];

    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}
