<?php

function display_crafting_recipes(): string {
    $gallery_details = [
        "player_data" => get_crafting_recipes_data(),
        "json_filename" => "crafting_recipes",
        "section_title" => "Crafting recipes"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}