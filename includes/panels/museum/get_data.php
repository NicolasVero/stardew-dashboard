<?php 

function get_museum_pieces_coords(): array {
    $untreated_all_data = $GLOBALS["untreated_all_players_data"];
	$museum_index = get_gamelocation_index($untreated_all_data, "museumPieces");
	$in_game_museum_pieces = $untreated_all_data->locations->GameLocation[$museum_index]->museumPieces;
	$museum_piece_details = [];

	foreach($in_game_museum_pieces->item as $museum_piece) {
		$museum_piece_id = (is_game_older_than_1_6()) ? (int) $museum_piece->value->int : (int) $museum_piece->value->string;
		$museum_piece_name = get_item_name_by_id($museum_piece_id);

		$museum_piece_details[$museum_piece_name] = [
			"id" => $museum_piece_id,
			"type" => get_museum_piece_type($museum_piece_name),
			"coords" => [
				"X" => (int) $museum_piece->key->Vector2->X,
				"Y" => (int) $museum_piece->key->Vector2->Y
			]
		];
	}

	usort($museum_piece_details, function($a, $b) {
		return $a["coords"]["X"] <=> $b["coords"]["X"];
	});

	return $museum_piece_details;
}

function get_museum_piece_type(string $piece_name): string {
	$artifacts = sanitize_json_with_version("artifacts", true);
	return (in_array($piece_name, $artifacts)) ? "artifacts" : "minerals";
}

function is_given_to_museum(int $item_id, object $general_data, int $museum_index): int { 

	$museum_items = $general_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach($museum_items->item as $museum_item) {
		$museum_item_id = (is_game_older_than_1_6()) ? (int) $museum_item->value->int : (int) $museum_item->value->string;

		if($item_id === $museum_item_id) {
			return 1;
		}
	}

	return 0;
}