<?php

function display_artifacts(): string {
    $gallery_details = [
        "player_data" => get_artifacts_data(),
        "json_filename" => "artifacts",
        "section_title" => "Artifacts"
    ];
    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}