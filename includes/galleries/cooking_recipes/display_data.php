<?php

function display_cooking_recipes(): string {
    $gallery_details = [
        "player_data" => get_cooking_recipes_data(),
        "json_filename" => "cooking_recipes",
        "section_title" => "Cooking recipes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}