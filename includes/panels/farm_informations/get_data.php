<?php
//! ONLY WORKS FOR 1.6.0+ RIGHT NOW

/**
 * Récupère les informations de la ferme comme l'ordinateur de ferme.
 * 
 * @return array Les informations de la ferme.
 */
function get_farm_informations(): array {
	$various_informations = get_various_farm_informations();

	$farm_infos = [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => $various_informations["total_crops"],
		"Crops Ready" => $various_informations["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => $various_informations["farm_cave_ready"]
	];

	log_($farm_infos);

	return [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => $various_informations["total_crops"],
		"Crops Ready" => $various_informations["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => $various_informations["farm_cave_ready"]
	];
}

/**
 * Récupère le nombre de pièces de foin dans la ferme.
 * 
 * @return int Le nombre de pièces de foin.
 */
function get_hay_pieces_in_farm(): int {
	$data = $GLOBALS["untreated_all_players_data"];
	$hay_count = 0;
	$hay_searches = [
		"locations.GameLocation.buildings.Building.indoors.piecesOfHay",
		"locations.GameLocation.piecesOfHay"
	];

	foreach($hay_searches as $hay_search) {
		$hay_locations = find_xml_tags($data, $hay_search);

		foreach($hay_locations as $hay_location) {
			if(($hay_amount = (int) $hay_location) === 0) {
				continue;
			}

			$hay_count += $hay_amount;
		}
	}

	return $hay_count;
}

/**
 * Récupère le nombre de pièces maximum de foin dans la ferme.
 * 
 * @return int Le nombre de pièces maximum de foin.
 */
function get_max_hay_pieces(): int {
	$data = $GLOBALS["untreated_all_players_data"];
	$hay_count = 0;
	$hay_locations = find_xml_tags($data, "locations.GameLocation.buildings.Building.hayCapacity");

	foreach($hay_locations as $hay_location) {
		if(($hay_amount = (int) $hay_location) === 0) {
			continue;
		}

		$hay_count += $hay_amount;
	}

	return $hay_count;
}

/**
 * Récupère diverses informations sur la ferme comme le nombre de récoltes et l'état de la caverne de la ferme.
 * 
 * @return array Les informations de la ferme.
 */
function get_various_farm_informations(): array {
	$data = $GLOBALS["untreated_all_players_data"];
	$crops_count = 0;
	$crops_ready_count = 0;
	$game_locations = find_xml_tags($data, "locations.GameLocation");

	foreach($game_locations as $game_location) {
		if((string) $game_location->name !== "Farm") {
			continue;
		}

		$is_farm_cave_ready = ((string) $game_location->farmCaveReady === "true");

		foreach($game_location->terrainFeatures->item as $crops_location) {
			if(!isset($crops_location->value->TerrainFeature->crop)) {
				continue;
			}

			$crops_location = $crops_location->value->TerrainFeature->crop;

			if((string) $crops_location->dead === "true") {
				continue;
			}
			
			$crops_count++;
	
			if((string) $crops_location->fullGrown === "true") {
				$crops_ready_count++;
			}
		}
	}

	return [
		"total_crops" => $crops_count,
		"crops_ready" => $crops_ready_count,
		"farm_cave_ready" => $is_farm_cave_ready
	];
}