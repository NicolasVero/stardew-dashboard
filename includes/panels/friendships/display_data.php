<?php

/**
 * Génère le code HTML pour afficher les informations d'amitié d'un villageois.
 *
 * @param array $friendship_info Informations sur l'amitié.
 * @return string Le code HTML pour afficher les informations d'amitié.
 */
function display_friendship_structure(array $friendship_info): string {
    $images_path = get_images_folder();
    $json_with_version = sanitize_json_with_version("villagers", true);
    
    $friendship_info = prepare_all_friendship_info($friendship_info);
    extract($friendship_info); //? $villager_name, $status, $points, $hearts_structure, $week_gifts, $wiki_link, $birthday[]
    extract($birthday); //? $is_birthday, $birthdate

    $formatted_villager_name = strtolower($villager_name);
    $version_class = array_search($villager_name, $json_with_version) ? "older-version" : "newer-version";
    $meet_class = ($status === "Unknown") ? "not-met" : "met";
    $birthday_class = ($is_birthday) ? "is_birthday" : "isnt_birthday";

    return "
        <span>
            <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                <span class='tooltip'>
                    <img src='$images_path/characters/$formatted_villager_name.png' class='character-icon $version_class $meet_class' alt='$villager_name icon'>
                    <span>$points " . __("friendship points") . "</span>
                </span>
            </a>
            <span class='character-name $formatted_villager_name'>" . __($villager_name) . "</span>
            <span class='hearts-level'>$hearts_structure</span>
            <span class='tooltip'> 
                <img src='$images_path/icons/birthday_icon.png' class='birthday_icon $birthday_class' alt=''>
                <span>$birthdate</span>
            </span>
            <span class='interactions'>
                <span class='tooltip'>
                    <img src='$images_path/icons/gift.png' class='interaction {$week_gifts[0]}' alt=''>
                    <img src='$images_path/icons/gift.png' class='interaction {$week_gifts[1]}' alt=''>
                    <span>" . __("Gifts made in the last week") . "</span>
                </span>
            </span>
            <span class='friend-status'>" . __($status) . "</span>
        </span>
    ";
}

/**
 * Génère le code HTML pour afficher les relations d'amitié du joueur.
 * 
 * @param int $limit Le nombre maximal de relations à afficher.
 * @return string Le code HTML des relations d'amitié affichées.
 */
function display_top_friendships(int $limit = 5): string {
    return display_friendships($limit);
}

/**
 * Génère le code HTML pour afficher les relations d'amitié du joueur.
 * 
 * @param int $limit Le nombre maximal de relations à afficher. 
 * @return string Le code HTML des relations d'amitié affichées.
 */
function display_friendships(int $limit = -1): string {
    $player_id = get_current_player_id();
    $friendship_data = sort_by_friend_level(get_friendships_data());
    $images_path = get_images_folder();
    $villagers_json = sanitize_json_with_version("villagers");
    
    $section_class = ($limit === -1) ? "all-friends" : "top-friends";
    $view_all = ($limit === -1) ? "" : "<span class='view-all-friends view-all-friends-$player_id modal-opener'>- " . __("View all friendships") . "</span>";
    $structure = ($limit === -1)
        ?
        "<section class='info-section friends-section $section_class $section_class-$player_id modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Friendship progression") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-friends-$player_id exit' alt='Exit'>
            </div>
            <span class='friendlist'>"
        :
        "<section class='info-section friends-section $section_class _50'>
            <span class='has_panel'>
                <h2 class='section-title'>" . __("Friendship progression") . "</h2>
                $view_all
            </span>
            <span class='friendlist'>";

        $all_villagers = array_merge(array_keys($friendship_data), array_diff($villagers_json, array_keys($friendship_data)));

        foreach($all_villagers as $villager_name) {
            if($limit === 0) {
                break;
            }
        
            if(isset($friendship_data[$villager_name])) {
                $structure .= display_friendship_structure($friendship_data[$villager_name]);
            } else {
                $structure .= display_friendship_structure(["id" => get_custom_id($villager_name)]);
            }
        
            $limit--;
        }

    $structure .= "
            </span>
        </section>
    ";
    return $structure;
}
