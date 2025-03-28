<?php

/**
 * Récupère les données des poissons pêchés par le joueur.
 * 
 * @return array Les données des poissons pêchés par le joueur.
 */
function get_player_fish_caught(): array {
	$player_fishes = $GLOBALS["untreated_player_data"]->fishCaught;
	$fishes_data = [];

	foreach($player_fishes->item as $fish) {
		$fish_id = (is_game_version_older_than_1_6()) ? $fish->key->int : $fish->key->string;
		$fish_id = format_original_data_string($fish_id);
		$fish_id = get_correct_id($fish_id);

		$values_array = (array) $fish->value->ArrayOfInt->int;
		$fish_reference = find_reference_in_json($fish_id, "fish");

		if(empty($fish_reference) || $fish_reference === "") {
			continue;
		}
		
		$fishes_data[$fish_reference] = [
			"id"             => (int) $fish_id,
			"caught_counter" => (int) $values_array[0],
			"max_length"     => (int) $values_array[1]
		];
	}

	return $fishes_data;
}
