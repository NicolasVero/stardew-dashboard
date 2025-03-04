<?php

function get_player_tools(): array {
	if(!is_game_singleplayer()) {
		return [];
	}
	# 1.6.0+ only
	# locations.GameLocation.buildings.Building.buildingChests.Chest.items

	# All versions
	# locations.GameLocation.objects.item.value.Object.items
	# locations.GameLocation.fridge.items
	# locations.GameLocation.buildings.Building.indoors.fridge.items
	# player.items

	# locations.GameLocation.buildings.Building.indoors.objects.item.value.Object.items

	$data = $GLOBALS["untreated_all_players_data"];
	$name_object = (is_game_older_than_1_6()) ? "Name" : "name";
	$player_items = [
		"Pickaxe" => "Pickaxe",
		"Axe" => "Axe",
		"Hoe" => "Hoe",
		"Can" => "Watering Can",
		"Pan" => "None",
		"Rod" => "None",
		"Scythe" => "Scythe"
	];

	# Check each item's 'Name' (< 1.6.0) || 'name' (> 1.6.0) to find the tool's name and level
	# /!\ For: 'Fishing Rods' and 'Scythes', player can have the lower level items
	$tool_names_dictionnary = [
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

				if(isset($tool_names_dictionnary[$tool_category])) {
					$tool_list = $tool_names_dictionnary[$tool_category];

					if(!isset($player_items[$tool_category]) || array_search($item_name, $tool_list) > array_search($player_items[$tool_category], $tool_list)) {
						$player_items[$tool_category] = $item_name;
					}
				}
			}
		}
	}
	
	log_($player_items);
	return [];
}