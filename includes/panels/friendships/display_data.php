<?php 

//& changer get en display 
function get_friendship_structure(array $friendship_info): string {
    extract($friendship_info);
    $friend_icon = "$images_path/characters/" . strtolower($name) . ".png";
    $is_newer_version = array_search($name, $json_with_version) ? "older-version" : "newer-version";
    
    $birthday = $birthday_json[get_custom_id($name)] ?? null;
    $is_birthday = $birthday && is_this_the_same_day($birthday) ? "is_birthday" : "isnt_birthday";
    $birthday_date = $birthday ? "Day " . explode("/", $birthday)[0] . " of " . explode("/", $birthday)[1] : "Unknown";
    
    $wiki_url = get_wiki_link(get_item_id_by_name($name));
    
    $friend_level = $friend["friend_level"] ?? 0;
    $status = $friend["status"] ?? "Unknown";
    $can_be_married = in_array($name, $marriables_npc) && $status === "Friendly";
    $max_heart = ($status) === "Married" ? 14 : 10;
    $is_met = ($status === "Unknown") ? "not-met" : "met";
    
    $hearts_html = "";

    for($i = 1; $i <= $max_heart; $i++) {
        $heart_icon = "$images_path/icons/" . (($i > 8 && $can_be_married) ? "locked_heart.png" : (($friend_level >= $i) ? "heart.png" : "empty_heart.png"));
        $hearts_html .= "<img src='$heart_icon' class='hearts' alt=''/>";
    }

    $gifted = ($friend) ? [
        $friend["week_gifts"] > 0 ? "gifted" : "not-gifted",
        $friend["week_gifts"] === 2 ? "gifted" : "not-gifted"
    ] : ["not-gifted", "not-gifted"];

    return "
        <span>
            <a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$friend_icon' class='character-icon $is_newer_version $is_met' alt='$name icon'/>
            </a>
            <span class='character-name " . strtolower($name) . "'>$name</span>
            <span class='hearts-level'>$hearts_html</span>
            <span class='tooltip'> 
                <img src='$images_path/icons/birthday_icon.png' class='birthday_icon $is_birthday' alt=''/>
                <span>$birthday_date</span>
            </span>
            <span class='interactions'>
                <span class='tooltip'>
                    <img src='$images_path/icons/gift.png' class='interaction {$gifted[0]}' alt=''/>
                    <img src='$images_path/icons/gift.png' class='interaction {$gifted[1]}' alt=''/>
                    <span>Gifts made in the last week</span>
                </span>
            </span>
            <span class='friend-status'>$status</span>
        </span>
    ";
}

function display_top_friendships(int $limit = 5): string {
    return display_friendships($limit);
}

function display_friendships(int $limit = -1): string {
    $player_id = get_current_player_id();
    $friendship_data = get_friendships_data();
    $images_path = get_images_folder();
    
    $marriables_npc = sanitize_json_with_version("marriables");
    $villagers_json = sanitize_json_with_version("villagers");
    $birthday_json = sanitize_json_with_version("villagers_birthday");
    $json_with_version = sanitize_json_with_version("villagers", true);
    
    $section_class = ($limit === -1) ? "all-friends" : "top-friends";
    $view_all = ($limit === -1) ? "" : "<span class='view-all-friends view-all-friends-$player_id modal-opener'>- View all friendships</span>";
    $structure = ($limit === -1)
        ? "
        <section class='info-section friends-section $section_class $section_class-$player_id modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>Friendship progression</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-friends-$player_id exit' alt='Exit'/>
            </div>
            <span class='friendlist'>
        "
        : "
        <section class='info-section friends-section $section_class _50'>
            <span class='has_panel'>
                <h2 class='section-title'>Friendship progression</h2>
                $view_all
            </span>
            <span class='friendlist'>
        ";

    foreach($friendship_data as $name => $friend) {
        if($limit === 0) {
            break;
        }
        
        $friendship_info = [
            "name" => $name,
            "friend" => $friend,
            "images_path" => $images_path,
            "marriables_npc" => $marriables_npc,
            "birthday_json" => $birthday_json,
            "json_with_version"=> $json_with_version
        ];

        $structure .= get_friendship_structure($friendship_info);
        $limit--;
    }

    foreach($villagers_json as $villager_name) {
        if($limit === 0) {
            break;
        }

        if(isset($friendship_data[$villager_name])) {   
            continue;
        }

        $friendship_info = [
            "name" => $villager_name,
            "friend" => null,
            "images_path" => $images_path,
            "marriables_npc" => $marriables_npc,
            "birthday_json" => $birthday_json,
            "json_with_version"=> $json_with_version
        ];
        
        $structure .= get_friendship_structure($friendship_info);
        $limit--;
    }

    $structure .= "
            </span>
        </section>";
    return $structure;
}
