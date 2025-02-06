<?php

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

function does_player_have_achievement(object $achievements, int $achievement_id): bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id !== $achievement) {
			continue;
		}

		return true;
	}

	return false;
}