<?php

/**
 * Génère le code HTML pour afficher les statistiques générales du joueur.
 * 
 * @return string Le code HTML des statistiques générales.
 */
function display_general_stats(): string {
	$all_players_data = get_general_data();
    $all_buttons = get_all_buttons();

    extract($all_players_data); //? all field "general" in extract_data_from_save.php

    $max_mine_level = 120;
    $deepest_mine_level = ($mine_level > $max_mine_level) ? $max_mine_level : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - $max_mine_level < 0) ? 0 : $mine_level - $max_mine_level;
    $deepest_mine_level_tooltip = $deepest_mine_level . __("floors in the Stardew Mine", SPACE_BOTH) . (($deepest_skull_mine_level > 0) ? "+ $deepest_skull_mine_level" . __("floors in the Skull Mine", SPACE_BEFORE) : "");

    return "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>" . __("General stats") . "</h2>
            <div class='panel-buttons'>
                $all_buttons
            </div>
            <div class='stats'>" .
                display_stat([
                    "icon" => "Energy", "label" => "max energy", "value" => $max_stamina, "wiki_link" => "Energy", "tooltip" => "$stardrops_found / 7 " . __("stardrops found") 
                ])
                .
                display_stat([
                    "icon" => "Health", "label" => "max health", "value" => $max_health, "wiki_link" => "Health"
                ])
                .
                display_stat([
                    "icon" => "Inventory", "label" => "inventory spaces", "value" => $max_items, "wiki_link" => "Inventory"
                ])
                .
                display_stat([
                    "icon" => "Mine level", "label" => "deepest mine level", "value" => $mine_level, "wiki_link" => "The_Mines", "tooltip" => $deepest_mine_level_tooltip
                ])
                .
                display_spouse($spouse, $children)
                .
                display_stat([
                    "icon" => "House", "alt" => "House upgrades", "label" => "upgrades done", "value" => $house_level, "wiki_link" => "Farmhouse", "tooltip" => "$house_level / 3 " . __("improvements")
                ])
                .
                display_stat([
                    "icon" => "Raccoons", "label" => "raccoons helped", "value" => $raccoons, "wiki_link" => "Giant_Stump", "tooltip" => "$raccoons / 10 " . __("missions for the raccoon family")
                ])
            . "</div>
        </section>
    ";
}

/**
 * Génère le code HTML pour afficher tous les boutons de panels.
 * 
 * @return string Le code HTML des boutons.
 */
function get_all_buttons(): string {
    $visited_locations_button = display_visited_locations_button();
	$tools_button = display_player_tools_button();
	$farm_informations_button = display_farm_informations_button();
	$community_center_button = display_community_center_button();
	$junimo_kart_button = display_junimo_kart_button();
	$quest_button = display_quest_button();

    return "
        $visited_locations_button
        $tools_button
        $farm_informations_button
        $community_center_button
        $junimo_kart_button
        $quest_button
    ";
}

/**
 * Génère le code HTML pour afficher une statistique générale.
 * 
 * @param array $parameters Les paramètres de la statistique.
 * @return string Le code HTML de la statistique.
 */
function display_stat(array $parameters): string {
    extract($parameters); //? $icon, $value, $tooltip, $alt, $label, $wiki_link

    $images_path = get_images_folder();
    $formatted_icon = format_text_for_file($icon);
    $formatted_value = filter_var($value, FILTER_VALIDATE_INT) ? format_number($value, $GLOBALS["site_language"]) : $value;
    $alt = $alt ?? $icon;
    $label = $label ?? $icon;
    $image = "<img src='$images_path/icons/$formatted_icon.png' alt='$alt'>";

    if(isset($tooltip)) {
        $image = "
            <span class='tooltip'>
                $image
                <span>$tooltip</span>
            </span>
        ";
    }

    if(isset($wiki_link)) {
        return "
            <a href='" . __("https://stardewvalleywiki.com/$wiki_link") . "' class='wiki_link' rel='noreferrer' target='_blank'>
                <span>
                    $image
                    <span class='data $formatted_icon'>$formatted_value</span>
                    <span class='data-label'>" . __($label) . "</span>
                </span>
            </a>
        ";
    }

    return "
        <span>
            $image
            <span class='data $formatted_icon'>$formatted_value</span>
            <span class='data-label'>" . __($label) . "</span>
        </span>
    ";
}

/**
 * Génère le code HTML pour afficher le conjoint du joueur.
 * 
 * @param mixed $spouse Le nom du conjoint.
 * @param array $children Les enfants du joueur.
 * @return string Le code HTML du conjoint.
 */
function display_spouse(mixed $spouse, array $children): string {
    if(empty($spouse)) {
        return "";
    }

    $images_path = get_images_folder();

    return "
        <a href='" . get_wiki_link_by_name("children") . "' class='wiki_link' rel='noreferrer' target='_blank'>
            <span>
                <span class='tooltip'>
                <img src='$images_path/characters/" . lcfirst($spouse) . ".png' alt='$spouse'>
                    <span>" . get_child_tooltip($spouse, $children) . "</span>
                </span>
                <span class='data data-family'>" . count($children) . "</span>
                <span class='data-label'>" . ((count($children) > 1) ? __("children") : __("child")) . "</span>
            </span>
        </a>
    ";
}
