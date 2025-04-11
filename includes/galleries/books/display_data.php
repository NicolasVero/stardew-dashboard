<?php

/**
 * Génère le code HTML de la galerie des livres.
 * 
 * @return string Le code HTML de la galerie des livres.
 */
function display_books(): string {
    $gallery_details = [
        "player_data" => get_books(),
        "json_filename" => "books",
        "section_title" => "Books"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
