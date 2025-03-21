<?php

/**
 * Récupère les données des minéraux trouvés par le joueur.
 * 
 * @return array Les données des minéraux trouvés par le joueur.
 */
function get_player_minerals(): array {
	$player_minerals = $GLOBALS["untreated_player_data"]->mineralsFound;
    $general_data = $GLOBALS["untreated_all_players_data"];
	$minerals_data = [];

	foreach($player_minerals->item as $mineral) {
		$mineral_id = ((is_game_version_older_than_1_6())) ? $mineral->key->int : $mineral->key->string;
		$mineral_id = format_original_data_string((string) $mineral_id);
		$mineral_id = get_correct_id($mineral_id);
		
		$minerals_reference = find_reference_in_json($mineral_id, "minerals");
		$museum_index = get_museum_index();

		if(!empty($minerals_reference)) {
			$minerals_data[$minerals_reference] = [
				"id"      => $mineral_id,
				"counter" => is_given_to_museum($mineral_id, $general_data, $museum_index)
			];
		}
	}
	
	return $minerals_data;
}