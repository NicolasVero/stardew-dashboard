<?php

/**
 * Génère le code HTML de la galerie des recettes de cuisine.
 * 
 * @return string Le code HTML de la galerie des recettes de cuisine.
 */
function display_cooking_recipes(): string {
    $gallery_details = [
        "player_data" => get_cooking_recipes(),
        "json_filename" => "cooking_recipes",
        "section_title" => "Cooking recipes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
