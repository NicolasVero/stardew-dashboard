<?php

/**
 * Génère le code HTML de la galerie des notes secrètes.
 * 
 * @return string Le code HTML de la galerie des notes secrètes.
 */
function display_secret_notes(): string {
    $gallery_details = [
        "player_data" => get_secret_notes_data(),
        "json_filename" => "secret_notes",
        "section_title" => "Secret notes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}