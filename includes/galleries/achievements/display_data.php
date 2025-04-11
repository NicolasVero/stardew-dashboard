<?php

/**
 * Génère le code HTML de la galerie des succès.
 * 
 * @return string Le code HTML de la galerie des succès.
 */
function display_achievements(): string {
    $gallery_details = [
        "player_data" => get_achievements(),
        "json_filename" => "achievements",
        "section_title" => "In-game achievements"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
