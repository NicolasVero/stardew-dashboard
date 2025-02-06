<?php

/**
 * Récupère les données des succès débloqués par le joueur.
 * 
 * @return array Les données des succès débloqués par le joueur.
 */
function get_player_achievements(): array {
    $player_achievements = $GLOBALS["untreated_player_data"]->achievements;
	$achievements_data = [];
	
	foreach($player_achievements->int as $achievement) {
		$achievement = find_reference_in_json((int) $achievement, "achievements_details");
		extract($achievement); //? $title, $description

		$achievements_data[$title] = [ "description" => $description ];
	}
	
	return $achievements_data;
}

/**
 * Vérifie si le joueur a débloqué un succès.
 * 
 * @return bool Indique si le joueur a débloqué le succès ou non.
 */
function does_player_have_achievement(object $achievements, int $achievement_id): bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id !== $achievement) {
			continue;
		}

		return true;
	}

	return false;
}