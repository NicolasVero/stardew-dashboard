<?php

/**
 * Génère le code HTML de la galerie des ennemis tués.
 * 
 * @return string Le code HTML de la galerie des ennemis tués.
 */
function display_enemies(): string {
    $gallery_details = [
        "player_data" => get_enemies_killed_data(),
        "json_filename" => "enemies",
        "section_title" => "Enemies killed"
    ];

    $panel_details = [
        "panel_alt"     => "monster-eradication-goals",
        "panel_name"    => "Monster Eradication Goals"
    ];
    
    return display_detailled_gallery($gallery_details, "_100", $panel_details);
}
