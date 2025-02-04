<?php 

function get_player_artifacts(): array {
	$player_artifacts = $GLOBALS["untreated_player_data"]->archaeologyFound;
    $general_data = $GLOBALS["untreated_all_players_data"];
	$artifacts_data = [];

	foreach($player_artifacts->item as $artifact) {

		$artifact_id = ((is_game_older_than_1_6())) ? $artifact->key->int : $artifact->key->string;
		$artifact_id = format_original_data_string((string) $artifact_id);
		$artifact_id = get_correct_id($artifact_id);

		$artifacts_reference = find_reference_in_json($artifact_id, "artifacts");
		$museum_index = get_gamelocation_index($general_data, "museumPieces");

		if(!empty($artifacts_reference)) {
			$artifacts_data[$artifacts_reference] = [
				"id"      => $artifact_id,
				"counter" => is_given_to_museum($artifact_id, $general_data, $museum_index)
			];
		}
	}
	
	return $artifacts_data;
}