<?php

function get_farm_informations(): array {
	$farm_infos = [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => get_crops_count()["total_crops"],
		"Crops Ready" => get_crops_count()["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => false
	];
	log_($farm_infos);

	return [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => get_crops_count()["total_crops"],
		"Crops Ready" => get_crops_count()["crops_ready"],
		"Unwatered Crops" => 0,
		"Open Tilled Soil" => 0,
		"Forage Items" => 0,
		"Machines Ready" => 0,
		"Farm Cave Ready" => false
	];
}

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

function get_crops_count(): array {
	$data = $GLOBALS["untreated_all_players_data"];
	$crops_count = 0;
	$crops_ready_count = 0;
	$crops_locations = find_xml_tags($data, "locations.GameLocation.terrainFeatures.item.value.TerrainFeature.crop");

	foreach($crops_locations as $crops_location) {
		if((string) $crops_location->dead === "true") {
			continue;
		}
		
		$crops_count++;

		if((string) $crops_location->fullGrown === "true") {
			$crops_ready_count++;
		}
	}

	return [
		"total_crops" => $crops_count,
		"crops_ready" => $crops_ready_count
	];
}