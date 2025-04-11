<?php

/**
 * Récupère les données des objets expédiés par le joueur.
 * 
 * @return array Les données des objets expédiés par le joueur.
 */
function get_player_shipped_items(): array {
	$player_items = $GLOBALS["current_player_raw_data"]->basicShipped;
	$shipped_items = [];

	foreach ($player_items->item as $item) {
		$item_id = (is_game_version_older_than_1_6()) ? $item->key->int : $item->key->string;
		$item_id = format_original_data_string($item_id);
		$item_id = get_correct_id($item_id);
		$counter = $item->value->int;

		$shipped_items_reference = find_reference_in_json($item_id, "shipped_items");

		if (empty($shipped_items_reference)) {
			continue;
		}

		$shipped_items[$shipped_items_reference] = [
			"id" => $item_id,
			"counter" => $counter
		];
	}
	
	return $shipped_items;
}
