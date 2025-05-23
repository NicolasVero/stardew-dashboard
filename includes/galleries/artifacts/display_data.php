<?php

/**
 * Génère le code HTML de la galerie des artéfacts.
 * 
 * @return string Le code HTML de la galerie des artéfacts.
 */
function display_artifacts(): string {
    $gallery_details = [
        "player_data" => get_artifacts(),
        "json_filename" => "artifacts",
        "section_title" => "Artifacts"
    ];
    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}
