<?php 

function display_general_stats(): string {
	$player_id = get_current_player_id();
	$all_players_data = get_general_data();
	$community_center_button = display_community_center_button();
	$junimo_kart_button = display_junimo_kart_button();
	$quest_button = display_quest_button();
    $visited_locations_button = display_visited_locations_button();

    extract($all_players_data);

    $max_mine_level = 120;
    $deepest_mine_level = ($mine_level > $max_mine_level) ? $max_mine_level : $mine_level; 
    $deepest_skull_mine_level = ($mine_level - $max_mine_level < 0) ? 0 : $mine_level - $max_mine_level;
    $deepest_mine_level_tooltip = "$deepest_mine_level floors in the Stardew Mine" . (($deepest_skull_mine_level > 0) ? " & $deepest_skull_mine_level floors in the Skull Mine" : "");

    return "
        <section class='info-section general-stats'>
        	<h2 class='section-title'>General stats</h2>
            $visited_locations_button
            $community_center_button
            $junimo_kart_button
			$quest_button
            <div>" .
                display_stat([
                    "icon" => "Energy", "label" => "max energy", "value" => $max_stamina, "wiki_link" => "Energy", "tooltip" => "$stardrops_found / 7 stardrops found" 
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
                    "icon" => "House", "alt" => "House upgrades", "label" => "upgrades done", "value" => $house_level, "wiki_link" => "Farmhouse", "tooltip" => "$house_level / 3 improvements"
                ])
                .
                display_stat([
                    "icon" => "Raccoons", "label" => "raccoons helped", "value" => $raccoons, "wiki_link" => "Giant_Stump", "tooltip" => "$raccoons / 10 missions for the raccoon family"
                ])
            . "</div>
        </section>
    ";
}

// [] --> *icon, *value, tooltip, alt, label, wiki_link
function display_stat(array $parameters): string {
    extract($parameters);
    $images_path = get_images_folder();
    $formatted_icon = formate_text_for_file($icon);
    $formatted_value = filter_var($value, FILTER_VALIDATE_INT) ? formate_number($value) : $value;
    $alt = $alt ?? $icon;
    $label = $label ?? $icon;
    $image = "<img src='$images_path/icons/$formatted_icon.png' alt='$alt'/>";

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
            <a href='https://stardewvalleywiki.com/$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                <span>
                    $image
                    <span class='data $formatted_icon'>$formatted_value</span>
                    <span class='data-label'>$label</span>
                </span>
            </a>
        ";
    }

    return "
        <span>
            $image
            <span class='data $formatted_icon'>$formatted_value</span>
            <span class='data-label'>$label</span>
        </span>
    ";
}

function display_spouse(mixed $spouse, array $children): string {
    if(empty($spouse)) {
        return "";
    }

    $images_path = get_images_folder();
    return "
        <span>
            <span class='tooltip'>
                <a href='" . get_wiki_link_by_name("children") . "' class='wiki_link' rel='noreferrer' target='_blank'>
                    <img src='$images_path/characters/" . lcfirst($spouse) . ".png' alt='$spouse'/>
                </a>
                <span> " . get_child_tooltip($spouse, $children) . "</span>
            </span>
            <span class='data data-family'>" . count($children) . "</span>
            <span class='data-label'>" . ((count($children) > 1) ? 'children' : 'child') . "</span>
        </span>
    ";
}