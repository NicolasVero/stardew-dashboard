<?php

/**
 * Génère le code HTML de la galerie des recettes d'artisanat.
 * 
 * @return string Le code HTML de la galerie des recettes d'artisanat.
 */
function display_crafting_recipes(): string {
    $gallery_details = [
        "player_data" => get_crafting_recipes_data(),
        "json_filename" => "crafting_recipes",
        "section_title" => "Crafting recipes"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}