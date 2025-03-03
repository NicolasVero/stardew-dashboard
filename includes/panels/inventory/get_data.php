<?php

function get_player_tools(): array {
	if(!is_game_singleplayer()) {
		return [];
	}
	# 1.6.0 only
	# locations.GameLocation.buildings.Building.buildingChests.Chest.items

	# All versions
	# locations.GameLocation.objects.item.value.Object.items
	# locations.GameLocation.fridge.items
	# locations.GameLocation.buildings.Building.indoors.fridge.items
	# player.items

	# locations.GameLocation.buildings.Building.indoors.objects.item.value.Object.items

	$data = $GLOBALS["untreated_all_players_data"];
	$player_items_locations = [
		"locations.GameLocation.buildings.Building.buildingChests.Chest.items",
		"locations.GameLocation.objects.item.value.Object.items",
		"locations.GameLocation.fridge.items",
		"locations.GameLocation.buildings.Building.indoors.fridge.items",
		"player.items",
		"locations.GameLocation.buildings.Building.indoors.objects.item.value.Object.items"
	];

	foreach($player_items_locations as $location) {
		$items[] = find_xml_tags($data, $location);
	}

	// log_($items);
	
	return [];
}