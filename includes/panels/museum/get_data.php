<?php

/**
 * Récupère les coordonnées des pièces du musée.
 *
 * @return array Les coordonnées des pièces du musée.
 */
function get_museum_pieces_coords(): array {
    $raw_data = $GLOBALS["raw_xml_data"];
	$museum_index = get_museum_index();
	$in_game_museum_pieces = $raw_data->locations->GameLocation[$museum_index]->museumPieces;
	$museum_piece_details = [];

	foreach ($in_game_museum_pieces->item as $museum_piece) {
		$museum_piece_id = (is_game_version_older_than_1_6()) ? (int) $museum_piece->value->int : (int) $museum_piece->value->string;
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

	usort($museum_piece_details, function(array $a, array $b): bool {
		return $a["coords"]["X"] <=> $b["coords"]["X"];
	});

	return $museum_piece_details;
}

/**
 * Récupère le type de pièce du musée.
 *
 * @param string $piece_name Le nom de la pièce.
 * @return string Le type de pièce du musée.
 */
function get_museum_piece_type(string $piece_name): string {
	$artifacts = sanitize_json_with_version("artifacts", true);
	return (in_array($piece_name, $artifacts)) ? "artifacts" : "minerals";
}

/**
 * Vérifie si un objet est donné au musée.
 *
 * @param int $item_id L'ID de l'objet.
 * @param object $raw_data Les données non-traitées du jeu.
 * @param int $museum_index L'index du musée.
 * @return int Indique si l'objet est donné au musée.
 */
function is_given_to_museum(int $item_id, object $raw_data, int $museum_index): int { 
	$museum_items = $raw_data->locations->GameLocation[$museum_index]->museumPieces;

	foreach ($museum_items->item as $museum_item) {
		$museum_item_id = (is_game_version_older_than_1_6()) ? (int) $museum_item->value->int : (int) $museum_item->value->string;

		if ($item_id === $museum_item_id) {
			return 1;
		}
	}

	return 0;
}
