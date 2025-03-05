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
	$tools_categories = get_correct_categories();

	$normalized_tools_dictionary = [];
	foreach ($tools_name_dictionary as $key => $tools) {
		$normalized_key = explode("/", $key)[0];
		$normalized_tools_dictionary[$normalized_key] = $tools;
	}

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

				if($tool_category === "Rod" || $tool_category === "Pole") {
					$tool_category = "Rod-Pole";
				}

				$tool_list = $normalized_tools_dictionary[$tool_category] ?? null;

				if($tool_list === null) {
					continue;
				}

				if(array_search($item_name, $tool_list) > array_search($player_items[$tools_categories[$tool_category]], $tool_list)) {
					$player_items[$tools_categories[$tool_category]] = $item_name;
				}
			}
		}
	}

	// Separate search for the player's trashcan
	$trashcan_level = (int) $data->player->trashCanLevel;
	$player_trashcan = [
		"Trash Can",
		"Copper Trash Can",
		"Steel Trash Can",
		"Gold Trash Can",
		"Iridium Trash Can"
	][$trashcan_level];
	$player_items["Trash Can/Trash Cans"] = $player_trashcan;

	return $player_items;
}

/**
 * Récupère les noms corrects des catégories d'outil.
 * 
 * @return array Les noms des catégories.
 */
function get_correct_categories(): array {
	return [
		"Pickaxe" => "Pickaxe/Pickaxes",
		"Axe" => "Axe/Axes",
		"Hoe" => "Hoe/Hoes",
		"Can" => "Can/Watering Cans",
		"Pan" => "Pan/Pans",
		"Rod-Pole" => "Rod-Pole/Fishing Poles",
		"Scythe" => "Scythe/Scythes",
		"Trash Can" => "Trash Can/Trash Cans"
	];
}

/**
 * Récupère le dictionnaire des noms d'outils.
 * 
 * @return array Les noms d'outils.
 */
function get_tools_dictionary(): array {
	return [
		"Pickaxe/Pickaxes" => [
			"Pickaxe",
			"Copper Pickaxe",
			"Steel Pickaxe",
			"Gold Pickaxe",
			"Iridium Pickaxe"
		],
		"Axe/Axes" => [
			"Axe",
			"Copper Axe",
			"Steel Axe",
			"Gold Axe",
			"Iridium Axe"
		],
		"Hoe/Hoes" => [
			"Hoe",
			"Copper Hoe",
			"Steel Hoe",
			"Gold Hoe",
			"Iridium Hoe"
		],
		"Can/Watering Cans" => [
			"Watering Can",
			"Copper Watering Can",
			"Steel Watering Can",
			"Gold Watering Can",
			"Iridium Watering Can"
		],
		"Pan/Pans" => [
			"None",
			"Pan",
			"Copper Pan",
			"Steel Pan",
			"Gold Pan",
			"Iridium Pan"
		],
		"Rod-Pole/Fishing Poles" => [
			"None",
			"Training Rod",
			"Bamboo Pole",
			"Fiberglass Rod",
			"Iridium Rod",
			"Advanced Iridium Rod"
		],
		"Scythe/Scythes" => [
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
		"Pickaxe/Pickaxes" => "Pickaxe",
		"Axe/Axes" => "Axe",
		"Hoe/Hoes" => "Hoe",
		"Can/Watering Cans" => "Watering Can",
		"Pan/Pans" => "None",
		"Rod-Pole/Fishing Poles" => "None",
		"Scythe/Scythes" => "Scythe",
		"Trash Can/Trash Cans" => "Trash Can"
	];
}