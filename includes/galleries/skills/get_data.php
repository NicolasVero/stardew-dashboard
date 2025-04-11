<?php

/**
 * Génère le code HTML d'une barre de progression du niveau de la compétence actuel du joueur.
 * 
 * @param int $level Niveau actuel de la compétence.
 * @param int $max_level Niveau maximum de la compétence.
 * @return string Code HTML de la barre de progression.
 */
function get_level_progress_bar(int $level, int $max_level = 10): string {
    $images_path = get_images_folder();
    $level_structure = "";
    
    for ($i = 1; $i <= $max_level; $i++) {
        $state = ($level >= $i) ? "" : "_empty";
        $icon_type = ($i % ($max_level / 2) === 0) ? "big_level" : "level";
        $level_bar = $images_path . "/icons/{$icon_type}{$state}.png";
        
        $level_structure .= "<img src='$level_bar' alt=''>";
    }
    
    return "
        <span class='level-progress-bar'>
            $level_structure
        </span>
    ";
}

/**
 * Génère le code HTML des icônes des compétences du joueur.
 * 
 * @param array $skills Liste des compétences du joueur.
 * @param string $current_skill Compétence actuelle du joueur.
 * @return string Code HTML des icônes des compétences.
 */
function get_skills_icons(array $skills, string $current_skill): string {
    $images_path = get_images_folder();
    $skill_structure = "";

    foreach ($skills as $skill) {
        if ($current_skill !== strtolower($skill["source"])) {
            continue;
        }

        $skill_icon = strtolower($skill["skill"]);
        $skill_icon_path = "$images_path/skills/$skill_icon.png";
        $skill_description = $skill["description"];

        $skill_structure .= "
            <span class='tooltip'>
                <img src='$skill_icon_path' alt='" . __($skill_description) . "'>
                <span>" . __($skill_description) . "</span>
            </span>
        ";
    }

    return "
        <div class='skills-section'>
            $skill_structure
        </div>
    ";
}

/**
 * Renvoie les données des compétences du joueur.
 * 
 * @return array Données des compétences du joueur.
 */
function get_player_skills_data(): array {
	$player_skills = (array) $GLOBALS["current_player_raw_data"]->professions->int;
	$json_skills = sanitize_json_with_version("skills");
	$skills = [];

	foreach ($json_skills as $key => $skill) {
		if (!in_array($key, $player_skills)) {
			continue;
		}

		$skills[] = $json_skills[$key];
	}

	return $skills;
}

/**
 * Renvoie les données de maîtrise du joueur.
 * 
 * @return array Données de maîtrise du joueur.
 */
function get_player_masteries(): array {
	$player_masteries = $GLOBALS["current_player_raw_data"]->stats->Values;
	return get_player_items_list($player_masteries, "masteries");
}

/**
 * Renvoie le niveau total de compétences du joueur.
 * 
 * @return int Niveau total de compétences du joueur.
 */
function get_total_skills_level(): int {
    $player_raw_data = $GLOBALS["current_player_raw_data"];
	$skill_types = [
		"farmingLevel",
		"miningLevel",
		"combatLevel",
		"foragingLevel",
		"fishingLevel"
	];

	$total_levels = 0;
	foreach ($skill_types as $skill) {
		$total_levels += $player_raw_data->$skill;
	}

	return $total_levels;
}
