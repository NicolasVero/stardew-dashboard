<?php

function get_player_tools(): array {
	if(!is_game_singleplayer()) {
		return [];
	}

	
	# 1.6.0 only
	# /locations/GameLocation/buildings/Building/buildingChests/Chest/items

	# /locations/GameLocation/objects/item/value/Object/items
	# /locations/GameLocation/fridge/items
	# /locations/GameLocation/buildings/Building/indoors/fridge/items
	# /player/items

	# /locations/GameLocation/buildings/Building/indoors/objects/item/value/Object/items
}