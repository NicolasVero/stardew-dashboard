<?php

function display_panels(): string {
	$structure  = display_friendships();
	$structure .= display_quest_panel();
    $structure .= display_visited_locations_panel();
	$structure .= display_monster_eradication_goals_panel();
	$structure .= display_calendar_panel();
	$structure .= display_farm_animals_panel();
	$structure .= display_junimo_kart_panel();
	$structure .= display_museum_panel();
	$structure .= display_community_center_panel();
    
    return $structure;
}

function display_skills(): string {
    $images_path = get_images_folder();
	$player_skills = get_skills_data();
	$player_skills_levels = get_levels_data();
	$player_masteries = get_masteries_data();
    $skill_structure = "";

    $mastery_visible_class = (empty($player_masteries)) ? "" : "not-hide";

    foreach($player_skills_levels as $key => $level) {
        $level_icon_name = explode('_', $key)[0];
        $mastery_class   = (array_key_exists(ucfirst(explode('_', $key)[0]) . " Mastery", $player_masteries)) ? 'found' : 'not-found';
        $mastery_tooltip = ucfirst(explode('_', $key)[0]) . " mastery";
        $is_newer_version_class = (is_game_older_than_1_6()) ? "newer-version" : "older-version";

        $skill_structure .= "
            <span class='skill $key'>
                <span class='tooltip'>
                    <a href='" . get_wiki_link_by_name("mastery_cave") . "' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$images_path/skills/mastery.png' class='level-icon $mastery_class $mastery_visible_class $is_newer_version_class' alt='$key'/>
                    </a>
                    <span>" . ucfirst($mastery_tooltip) . "</span>
                </span>
        
                <span class='tooltip'>
                    <a href='" . get_wiki_link_by_name("skills") . "#" . ucfirst($level_icon_name) . "' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$images_path/skills/$level_icon_name.png' class='level-icon' alt='$key'/>
                    </a>
                    <span>" . ucfirst($level_icon_name) . "</span>
                </span>
                " . get_level_progress_bar($level) . "
                <span class='level data'>$level</span>
                <span>
                    <a href='" . get_wiki_link_by_name("skills") . "' class='wiki_link' rel='noreferrer' target='_blank'>" 
                        . get_skills_icons($player_skills, $level_icon_name) . "
                    </a>
                </span>
            </span>
        ";
    }

    return "
		<section class='skills-section info-section'>
			<h2 class='section-title'>Skills</h2>
            <span>
                $skill_structure
            </span>
        </section>
    ";
}






function display_detailled_gallery(array $gallery_details, string $width = "", array $panel_details = []): string {
    extract($gallery_details);
	$version_score = $GLOBALS["game_version_score"];
	$images_path = get_images_folder();
    $json_data = $GLOBALS["json"][$json_filename];

    extract($panel_details);

    $player_id = get_current_player_id();
    $title = (!empty($panel_details)) ?
        "<span class='has_panel'>
            <h2 class='section-title'>$section_title</h2>
            <span class='view-$panel_alt view-$panel_alt-$player_id modal-opener'>- View $panel_name</span>
        </span>"
        :
        "<h2 class='section-title'>$section_title</h2>";

    $structure = "
        <section class='gallery $json_filename-section $width'>
            $title
            <span>
				<h3 class='no-spoil-title'>" . no_items_placeholder() . "</h3>
    ";
    
    foreach($json_data as $key => $json_version) {
        $is_newer_version_class = ($version_score < get_game_version_score($key)) ? "newer-version" : "older-version";
        
        foreach($json_version as $json_line_name) {
            $is_found = array_key_exists($json_line_name, $player_data);
            $element_class = ($is_found) ? "found" : "not-found";

            // Wilderness Golem désactivé si pas la ferme wilderness
            if($json_filename === "enemies" && $json_line_name === "Wilderness Golem" && $GLOBALS["should_spawn_monsters"] === "false") {
                continue;
            }

            if(in_array($json_filename, ["cooking_recipes", "crafting_recipes", "artifacts", "minerals"])) {
                if($is_found && $player_data[$json_line_name]["counter"] === 0) {
                    $element_class .= " unused";
                }
            }

            $element_image = "$images_path/$json_filename/" . formate_text_for_file((string) explode('µ', $json_line_name)[0]). ".png";
            if(in_array($json_filename, ["secret_notes"])) {
                $line_name = explode(" ", $json_line_name);
                $icon_name = formate_text_for_file(implode(" ", array_slice($line_name, 0, 2)));
                $element_image = "$images_path/icons/$icon_name.png";
            }
            
            
            if(in_array($json_filename, ["locations_to_visit"])) {
                $element_image = [
                    "casino"           => "$images_path/icons/casino_coins.png",
                    "calico_desert"    => "$images_path/shipped_items/cactus_fruit.png",
                    "skull_cavern"     => "$images_path/enemies/haunted_skull.png",
                    "greenhouse"       => "$images_path/crafting_recipes/quality_sprinkler.png",
                    "secret_woods"     => "$images_path/shipped_items/fiber.png",
                    "the_sewers"       => "$images_path/icons/trash.png",
                    "witchs_swamp"     => "$images_path/fish/void_salmon.png",
                    "quarry"           => "$images_path/crafting_recipes/bomb.png",
                    "ginger_island"    => "$images_path/shipped_items/ginger.png",
                    "qis_walnut_room"  => "$images_path/icons/qi_gems.png",
                    "the_summit"       => "$images_path/icons/stardrop.png",
                    "mastery_cave"     => "$images_path/skills/mastery.png",
                ][formate_text_for_file($json_line_name)];
            }

            if(in_array($json_filename, ["achievements", "secret_notes"])) {
                $wiki_url = [
                    "achievements" => get_wiki_link_by_name("achievements"),
                    "secret_notes" => get_wiki_link_by_name("secret_notes")
                ][$json_filename];
            } else {
                $wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
            }

            $element_tooltip = ($is_found) ? get_tooltip_text($player_data, $json_line_name, $json_filename) : $json_line_name;

			$structure .= "
				<span class='tooltip'>
					<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$element_image' class='gallery-item $json_filename $element_class $is_newer_version_class' alt='$json_line_name'/>
                    </a>
                    <span>$element_tooltip</span>
                </span>
            ";
        }
    }

	$structure .= "
			</span>
		</section>
	";

    return $structure;
}

function display_books(): string {
    $gallery_details = [
        "player_data" => get_books_data(),
        "json_filename" => "books",
        "section_title" => "Books"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}



function display_cooking_recipes(): string {
    $gallery_details = [
        "player_data" => get_cooking_recipes_data(),
        "json_filename" => "cooking_recipes",
        "section_title" => "Cooking recipes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_minerals(): string {
    $gallery_details = [
        "player_data" => get_minerals_data(),
        "json_filename" => "minerals",
        "section_title" => "Minerals"
    ];
    $panel_details = [
        "panel_alt"     => "museum",
        "panel_name"    => "museum pieces"
    ];
    return display_detailled_gallery($gallery_details, "_50", $panel_details);
}

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

function display_enemies(): string {
    $gallery_details = [
        "player_data" => get_enemies_killed_data(),
        "json_filename" => "enemies",
        "section_title" => "Enemies killed"
    ];
    $panel_details = [
        "panel_alt"     => "monster-eradication-goals",
        "panel_name"    => "Monster Eradication Goals"
    ];
    return display_detailled_gallery($gallery_details, "_100", $panel_details);
}

function display_achievements(): string {
    $gallery_details = [
        "player_data" => get_achievements_data(),
        "json_filename" => "achievements",
        "section_title" => "In-game achievements"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}

function display_shipped_items(): string {
    $gallery_details = [
        "player_data" => get_shipped_items_data(),
        "json_filename" => "shipped_items",
        "section_title" => "Shipped items"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}

function display_crafting_recipes(): string {
    $gallery_details = [
        "player_data" => get_crafting_recipes_data(),
        "json_filename" => "crafting_recipes",
        "section_title" => "Crafting recipes"
    ];
    return display_detailled_gallery($gallery_details, "_100");
}

function display_farm_animals(): string {
    $gallery_details = [
        "player_data" => get_farm_animals_data(),
        "json_filename" => "farm_animals",
        "section_title" => "Farm animals"
    ];
    $panel_details = [
        "panel_alt"     => "all-animals",
        "panel_name"    => "all farm animals"
    ];
    return display_detailled_gallery($gallery_details, "", $panel_details);
}

function display_secret_notes(): string {
    $gallery_details = [
        "player_data" => get_secret_notes_data(),
        "json_filename" => "secret_notes",
        "section_title" => "Secret notes"
    ];
    return display_detailled_gallery($gallery_details, "_50");
}
