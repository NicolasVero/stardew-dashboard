<?php

function display_achievements(): string {
    $gallery_details = [
        "player_data" => get_achievements_data(),
        "json_filename" => "achievements",
        "section_title" => "In-game achievements"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}