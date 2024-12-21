<?php 

function display_books(): string {
    $gallery_details = [
        "player_data" => get_books_data(),
        "json_filename" => "books",
        "section_title" => "Books"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
