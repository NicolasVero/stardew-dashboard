<?php

function display_fish(): string {
    $gallery_details = [
        "player_data" => get_fish_data(),
        "json_filename" => "fish",
        "section_title" => "Fish caught"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}