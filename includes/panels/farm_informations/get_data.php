<?php
//! ONLY WORKS FOR 1.6.0+ RIGHT NOW

/**
 * Récupère les informations de la ferme comme l'ordinateur de ferme.
 * 
 * @return array Les informations de la ferme.
 */
function get_farm_informations(): array {
	$crops_count = get_crops_count();

	$farm_infos = [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => $crops_count["total_crops"],
		"Crops Ready" => $crops_count["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => false
	];

	log_($farm_infos);

	return [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => $crops_count["total_crops"],
		"Crops Ready" => $crops_count["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => false
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
 * Récupère le nombre de récoltes dans la ferme.
 * 
 * @return array Le nombre de récoltes.
 */
function get_crops_count(): array {
	$data = $GLOBALS["untreated_all_players_data"];
	$crops_count = 0;
	$crops_ready_count = 0;
	$game_locations = find_xml_tags($data, "locations.GameLocation");

	foreach($game_locations as $game_location) {
		if((string) $game_location->name !== "Farm") {
			continue;
		}

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
		"crops_ready" => $crops_ready_count
	];
}