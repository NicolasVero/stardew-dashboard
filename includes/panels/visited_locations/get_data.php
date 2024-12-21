<?php 

function get_player_visited_locations(): array {
	if(is_game_older_than_1_6()) {
		return [];
	}

	$player_data = $GLOBALS["untreated_player_data"];
	$locations_to_visit = sanitize_json_with_version("locations_to_visit");
	$player_visited_locations = [];
	$locations_real_name = [
		"Club" => "Casino",
		"Desert" => "Calico Desert",
		"SkullCave" => "Skull Cavern",
		"Greenhouse" => "Greenhouse",
		"Woods" => "Secret Woods",
		"Sewer" => "The Sewers",
		"WitchSwamp" => "Witch's Swamp",
		"IslandSouth" => "Ginger Island",
		"QiNutRoom" => "Qi's Walnut Room",
		"Summit" => "The Summit",
		"MasteryCave" => "Mastery Cave"
	];

	foreach($player_data->locationsVisited->string as $location_visited) {
		$location_name = (string) $location_visited;
		$location_real_name = $locations_real_name[$location_name] ?? "";

		if(in_array($location_real_name, $locations_to_visit)) {
			$player_visited_locations[$location_real_name] = [
				"id" => get_item_id_by_name($location_real_name)
			];
		}
	}

	$additional_locations = [
		"VisitedQuarryMine" => "Quarry"
	];

	foreach($additional_locations as $additional_location => $location_real_name) {
		if(has_element_in_mail($additional_location)) {
			$player_visited_locations[$location_real_name] = [
				"id" => get_item_id_by_name($location_real_name)
			];
		}
	}

	return $player_visited_locations;
}