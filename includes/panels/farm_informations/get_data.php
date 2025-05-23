<?php
/**
 * Récupère les informations de la ferme comme l'ordinateur de ferme.
 * 
 * @return array Les informations de la ferme.
 */
function get_farm_informations_data(): array {
	$informations = get_complex_farm_informations();

	return [
		"Pieces Hay" => get_hay_pieces_in_farm() . " / " . get_max_hay_pieces(),
		"Total Crops" => $informations["total_crops"],
		"Crops Ready" => $informations["crops_ready"],
		"Unwatered Crops" => $informations["unwatered_crops"],
		"Crops Ready In Greenhouse" => $informations["greenhouse_crops"],
		"Open Tilled Soil" => $informations["tilled_soils"],
		"Machines Ready" => $informations["machines_ready"],
		"Farm Cave Ready" => $informations["farm_cave_ready"]
	];
}

/**
 * Récupère diverses informations sur la ferme comme le nombre de récoltes et l'état de la caverne de la ferme.
 * 
 * @return array Les informations de la ferme.
 */
function get_complex_farm_informations(): array {
	$raw_data = $GLOBALS["raw_xml_data"];
	$game_locations = find_xml_tags($raw_data, "locations.GameLocation");

	foreach ($game_locations as $game_location) {		
		if ((string) $game_location->name === "Farm") {
			$is_farm_cave_ready = ((string) $game_location->farmCaveReady === "true") ? "Yes" : "No";
	
			$crops = get_crops_on_farm($game_location);
			$machines = get_machines_ready_on_farm($game_location);
			$tilled_soils = get_tilled_soil_count($game_location);
		}

		if ((string) $game_location->name === "Greenhouse") {
			$greenhouse_crops = get_crops_on_farm($game_location);
		}

		continue;
	}

	return [
		"total_crops" => $crops["total_crops"],
		"crops_ready" => $crops["crops_ready"],
		"unwatered_crops" => $crops["unwatered_crops"],
		"greenhouse_crops" => $greenhouse_crops["crops_ready"],
		"tilled_soils" => $tilled_soils,
		"machines_ready" => $machines,
		"farm_cave_ready" => $is_farm_cave_ready
	];
}

/**
 * Récupère le nombre de pièces de foin dans la ferme.
 * 
 * @return int Le nombre de pièces de foin.
 */
function get_hay_pieces_in_farm(): int {
	$raw_data = $GLOBALS["raw_xml_data"];
	$hay_count = 0;
	$hay_searches = [
		"locations.GameLocation.buildings.Building.indoors.piecesOfHay",
		"locations.GameLocation.piecesOfHay"
	];

	foreach ($hay_searches as $hay_search) {
		$hay_locations = find_xml_tags($raw_data, $hay_search);

		foreach ($hay_locations as $hay_location) {
			if (($hay_amount = (int) $hay_location) === 0) {
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
	$raw_data = $GLOBALS["raw_xml_data"];
	$hay_count = 0;
	$hay_locations = find_xml_tags($raw_data, "locations.GameLocation.buildings.Building.hayCapacity");

	foreach ($hay_locations as $hay_location) {
		if (($hay_amount = (int) $hay_location) === 0) {
			continue;
		}

		$hay_count += $hay_amount;
	}

	return $hay_count;
}

/**
 * Récupère des informations sur les récoltes dans la ferme.
 * 
 * @return array Les informations sur les récoltes.
 */
function get_crops_on_farm(SimpleXMLElement $game_location): array {
	$crops_count = 0;
	$crops_ready_count = 0;
	$unwatered_crops = 0;

	foreach ($game_location->terrainFeatures->item as $crops_location) {
		if (!isset($crops_location->value->TerrainFeature->crop)) {
			continue;
		}

		$crops_location = $crops_location->value->TerrainFeature;

		if ((string) $crops_location->crop->dead === "true") {
			continue;
		}
		
		$crops_count++;

		if ((int) $crops_location->state === 0) {
			$unwatered_crops++;
		}

		if ((string) $crops_location->crop->fullGrown === "true") {
			$crops_ready_count++;
		}
	}

	return [
		"total_crops" => $crops_count,
		"crops_ready" => $crops_ready_count,
		"unwatered_crops" => $unwatered_crops
	];
}

/**
 * Récupère le nombre de machines prêtes dans la ferme.
 * 
 * @return int Le nombre de machines prêtes.
 */
function get_machines_ready_on_farm(SimpleXMLElement $game_location): int {
	$machines_count = 0;

	foreach ($game_location->objects->item as $object) {
		if (!isset($object->value->Object->readyForHarvest) || (string) $object->value->Object->readyForHarvest === "false") {
			continue;
		}

		if ((string) $object->value->Object->readyForHarvest === "true") {
			$machines_count++;
		}
	}

	foreach ($game_location->buildings->Building as $building) {
		if (!isset($building->output) || empty((array) $building->output) || count((array) $building->output) > 1) {
			continue;
		}

		$machines_count++;
	}

	return $machines_count;
}

/**
 * Récupère le nombre de sols labourés prêts dans la ferme.
 * 
 * @return int Le nombre de sols labourés prêts.
 */
function get_tilled_soil_count(SimpleXMLElement $game_location): int {
	$tilled_soil_count = 0;

	foreach ($game_location->terrainFeatures->item as $soil) {
		if (isset($soil->value->TerrainFeature->state) && !isset($soil->value->TerrainFeature->crop)) {
			$tilled_soil_count++;
		}
	}

	return $tilled_soil_count;
}
