<?php

function display_shipped_items(): string {
    $gallery_details = [
        "player_data" => get_shipped_items_data(),
        "json_filename" => "shipped_items",
        "section_title" => "Shipped items"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}