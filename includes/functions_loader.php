<?php 

$functions_files = [
    "general_stats",
    "header",
    "topbar",
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

foreach($functions_files as $folder => $subfolders) {
    $folders_to_include = (is_array($subfolders)) ? $subfolders : [$subfolders];

    foreach ($folders_to_include as $subfolder) {
        include_files_if_exists("includes/{$folder}/{$subfolder}/get_data.php");
        include_files_if_exists("includes/{$folder}/{$subfolder}/display_data.php");
    }
}

function include_files_if_exists($path) {
    $path = preg_replace('/\/\d+/', '', $path);

    if(file_exists($path)) {
        require_once $path;
    }
}
