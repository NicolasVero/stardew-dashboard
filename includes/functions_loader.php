<?php 

$functions_files = [
    "topbar",
    "header",
    "general_stats",
    "galleries" => [
        "achievements",
        "artifacts",
        "books",
        "cooking_recipes",
        "crafting_recipes",
        "enemies",
        "farm_animals",
        "fish",
        "minerals",
        "secret_notes",
        "shipped_items",
        "skills"
    ],
    "panels" => [
        "calendar",
        "community_center",
        "eradication_goals",
        "farm_animals",
        "friendships",
        "junimo_kart",
        "museum",
        "quests",
        "unlockables",
        "visited_locations"
    ]
];

$path_prefix = (str_contains(debug_backtrace()[1]["file"], "get_xml_data.php")) ? "../" : "";
foreach($functions_files as $folder => $subfolders) {
    $folders_to_include = (is_array($subfolders)) ? $subfolders : [$subfolders];

    foreach($folders_to_include as $subfolder) {
        include_files_if_exists("{$path_prefix}includes/{$folder}/{$subfolder}/get_data.php");
        include_files_if_exists("{$path_prefix}includes/{$folder}/{$subfolder}/display_data.php");
    }
}

function include_files_if_exists(string $path): void {
    $path = preg_replace("/\/\d+/", "", $path);

    if(file_exists($path)) {
        require_once $path;
    }
}