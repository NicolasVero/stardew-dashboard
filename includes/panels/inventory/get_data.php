<?php

/**
 * Récupère les outils du joueur uniquement si la partie est en singleplayer.
 * 
 * @return array Les outils du joueur.
 */
function get_player_tools(): array {
	if(!is_game_singleplayer()) {
		return [];
	}

	$data = $GLOBALS["untreated_all_players_data"];
	$name_object = (is_game_older_than_1_6()) ? "Name" : "name";
	$player_items = get_starting_tools();
	$tools_name_dictionary = get_tools_dictionary();

	$player_items_locations = [
		"player.items",
		"locations.GameLocation.objects.item.value.Object.items",
		"locations.GameLocation.buildings.Building.indoors.objects.item.value.Object.items",
		"locations.GameLocation.buildings.Building.buildingChests.Chest.items",
		"locations.GameLocation.fridge.items",
		"locations.GameLocation.buildings.Building.indoors.fridge.items",
	];

	foreach($player_items_locations as $location) {
		$items_arrays = find_xml_tags($data, $location);

		foreach($items_arrays as $items_array) {
			foreach($items_array as $item) {
				$item_name = (string) $item->$name_object;
				$last_item_word = explode(" ", $item_name);
				$tool_category = end($last_item_word);
				$tool_list = $tools_name_dictionary[$tool_category] ?? null;

				if($tool_list === null) {
					continue;
				}

				if(array_search($item_name, $tool_list) > array_search($player_items[$tool_category], $tool_list)) {
					$player_items[$tool_category] = $item_name;
				}
			}
		}
	}

	return $player_items;
}

/**
 * Récupère le dictionnaire des noms d'outils.
 * 
 * @return array Les noms d'outils.
 */
function get_tools_dictionary(): array {
	return [
		"Pickaxe" => [
			"Pickaxe",
			"Copper Pickaxe",
			"Steel Pickaxe",
			"Gold Pickaxe",
			"Iridium Pickaxe"
		],
		"Axe" => [
			"Axe",
			"Copper Axe",
			"Steel Axe",
			"Gold Axe",
			"Iridium Axe"
		],
		"Hoe" => [
			"Hoe",
			"Copper Hoe",
			"Steel Hoe",
			"Gold Hoe",
			"Iridium Hoe"
		],
		"Can" => [
			"Watering Can",
			"Copper Watering Can",
			"Steel Watering Can",
			"Gold Watering Can",
			"Iridium Watering Can"
		],
		"Pan" => [
			"None",
			"Pan",
			"Copper Pan",
			"Steel Pan",
			"Gold Pan",
			"Iridium Pan"
		],
		"Rod" => [
			"None",
			"Training Rod",
			"Bamboo Pole",
			"Fiberglass Rod",
			"Iridium Rod",
			"Advanced Iridium Rod"
		],
		"Scythe" => [
			"Scythe",
			"Golden Scythe",
			"Iridium Scythe"
		]
	];
}

/**
 * Récupère les outils de départ d'une partie.
 * 
 * @return array Les noms d'outils.
 */
function get_starting_tools(): array {
	return [
		"Pickaxe" => "Pickaxe",
		"Axe" => "Axe",
		"Hoe" => "Hoe",
		"Can" => "Watering Can",
		"Pan" => "None",
		"Rod" => "None",
		"Scythe" => "Scythe"
	];
}