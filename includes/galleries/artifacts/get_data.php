<?php

/**
 * Récupère les données des artéfacts trouvés par le joueur.
 * 
 * @return array Les données des artéfacts trouvés par le joueur.
 */
function get_player_artifacts(): array {
	$player_artifacts = $GLOBALS["current_player_raw_data"]->archaeologyFound;
    $raw_data = $GLOBALS["raw_xml_data"];
	$artifacts = [];

	foreach ($player_artifacts->item as $artifact) {
		$artifact_id = ((is_game_version_older_than_1_6())) ? $artifact->key->int : $artifact->key->string;
		$artifact_id = format_original_data_string((string) $artifact_id);
		$artifact_id = get_correct_id($artifact_id);

		$artifacts_reference = find_reference_in_json($artifact_id, "artifacts");
		$museum_index = get_museum_index();

		if (empty($artifacts_reference)) {
			continue;
		}
		
		$artifacts[$artifacts_reference] = [
			"id"      => $artifact_id,
			"counter" => is_given_to_museum($artifact_id, $raw_data, $museum_index)
		];
	}
	
	return $artifacts;
}
