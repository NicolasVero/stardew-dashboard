<?php 

function get_level_progress_bar(int $level, int $max_level = 10): string {
    $images_path = get_images_folder();
    $level_structure = "";
    
    for($i = 1; $i <= $max_level; $i++) {
        $state = ($level >= $i) ? "" : "_empty";
        $icon_type = ($i % ($max_level / 2) === 0) ? "big_level" : "level";
        $level_bar = $images_path . "/icons/{$icon_type}{$state}.png";
        
        $level_structure .= "<img src='$level_bar' alt=''/>";
    }
    
    return "
        <span class='level-progress-bar'>
            $level_structure
        </span>
    ";
}

function get_skills_icons(array $skills, string $current_skill): string {
    $images_path = get_images_folder();
    $skill_structure = "";

    foreach($skills as $skill) {
        if($current_skill === strtolower($skill["source"])) {

            $skill_icon = strtolower($skill["skill"]);
            $skill_icon_path = "$images_path/skills/$skill_icon.png";
            $skill_description = $skill["description"];

            $skill_structure .= "
                <span class='tooltip'>
                    <img src='$skill_icon_path' alt='$skill_description'/>
                    <span>$skill_description</span>
                </span>
			";
        }
    }

    return "
        <div class='skills-section'>
            $skill_structure
        </div>
    ";
}

function get_player_skills_data(): array {
	$player_skills = (array) $GLOBALS["untreated_player_data"]->professions->int;
	$json_skills = sanitize_json_with_version("skills");
	$skills_data = [];

	foreach($json_skills as $key => $skill) {
		if(!in_array($key, $player_skills)) {
			continue;
		}

		$skills_data[] = $json_skills[$key];
	}

	return $skills_data;
}

function get_player_masteries(): array {
	$player_masteries = $GLOBALS["untreated_player_data"]->stats->Values;
	return get_player_items_list($player_masteries, "masteries");
}

function get_total_skills_level(): int {
    $player_data = $GLOBALS["untreated_player_data"];
	$skill_types = [
		"farmingLevel",
		"miningLevel",
		"combatLevel",
		"foragingLevel",
		"fishingLevel"
	];

	$total_levels = 0;
	foreach($skill_types as $skill) {
		$total_levels += $player_data->$skill;
	}

	return $total_levels;
}