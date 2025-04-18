<?php

/**
 * Renvoie le hash binaire du Centre communautaire de la sauvegarde.
 * 
 * @param array $player_bundles Les bundles du joueur.
 * @return string Le hash binaire du Centre communautaire.
 */
function get_cc_binary_hash(array $player_bundles): string {
	$bundles_json = sanitize_json_with_version("bundles", true);
	$room_indexes = [];
	$binary_result = "";

	foreach ($bundles_json as $room_name => $room_details) {
		$room_indexes[$room_name] = [];
		
		foreach ($room_details["bundle_ids"] as $id) {
			$room_indexes[$room_name][] = [$id => false];
		}
	}

	foreach ($player_bundles as $bundle_id => $player_bundle) {
		if (empty($player_bundle["is_complete"]) || $player_bundle["is_complete"] === false) {
			continue;
		}

		foreach ($room_indexes[$player_bundle["room_name"]] as &$bundle) {
			if (!isset($bundle[$bundle_id])) {
				continue;
			}
			
			$bundle[$bundle_id] = true;
		}
	}

	foreach ($room_indexes as $room_name => $bundles) {
		$all_complete = true;

		foreach ($bundles as $bundle) {
			if (!in_array(false, $bundle)) {
				continue;
			}

			$all_complete = false;
			break;
		}
		
		$binary_result .= $all_complete ? "1" : "0";
	}

	$binary_result = str_pad($binary_result, 6, "0", STR_PAD_RIGHT);
	return $binary_result;
}

/**
 * Renvoie les détails de la progression d'un bundle du joueur.
 * 
 * @param array $player_bundles Les bundles du joueur.
 * @param object $bundle_data Les données du bundle.
 * @return array Les détails du bundle.
 */
function get_player_bundle_progress(object $bundle_data, array $bundle_progress): array {
	$bundle_details = get_player_bundle_details($bundle_data);
	$bundle_details["is_complete"] = false;
	$bundle_details["items_added"] = [];
	
	$bundle_details = [
		"room_name" => $bundle_progress["room_name"]
	] + $bundle_details;

	if (empty($bundle_details["limit"])) {
		$bundle_details["limit"] = count($bundle_details["requirements"]);
	}

	$is_bundle_completed = is_bundle_completed($bundle_progress["room_name"], $bundle_progress["progress"]);

	if ($is_bundle_completed) {
		$bundle_details["is_complete"] = true;
		return $bundle_details;
	}

	for ($item_in_bundle = 0; $item_in_bundle < count($bundle_details["requirements"]); $item_in_bundle++) {
		if ($bundle_progress["progress"][$item_in_bundle] !== "true") {
			continue;
		}
		
		array_push($bundle_details["items_added"], $bundle_details["requirements"][$item_in_bundle]);
	}

	return $bundle_details;
}

/**
 * Vérifie si un bundle est complété.
 * 
 * @param string $room_name Le nom de la salle.
 * @param array $progress La progression du bundle.
 * @return bool Indique si le bundle est complété.
 */
function is_bundle_completed(string $room_name, array $progress): bool {
	$cc_rooms = [
        "Boiler Room" => "ccBoilerRoom",
		"Crafts Room" => "ccCraftsRoom",
		"Pantry" => "ccPantry",
        "Fish Tank" => "ccFishTank",
		"Vault" => "ccVault",
		"Bulletin Board" => "ccBulletin"
    ];

	$joja_rooms = [
        "Boiler Room" => "jojaBoilerRoom",
		"Crafts Room" => "jojaCraftsRoom",
		"Pantry" => "jojaPantry",
        "Fish Tank" => "jojaFishTank",
		"Vault" => "jojaVault",
		"Bulletin Board" => "JojaMember"
    ];

	// Les bundles sont entièrement constitués de "true" si il a été complété SAUF pour les bundles de "Vault"
	$is_bundle_completed = ($room_name !== "Vault") ?
	(
		!in_array("false", $progress, true)
		||
		has_element_in_mail($cc_rooms[$room_name])
		||
		has_element_in_mail($joja_rooms[$room_name])
	)
	:
	(
		$progress[0] === "true"
		||
		has_element_in_mail($cc_rooms[$room_name])
		||
		has_element_in_mail($joja_rooms[$room_name])
	);

	return $is_bundle_completed;
}

/**
 * Renvoie les détails d'un bundle.
 * 
 * @param object $bundle_data Les données du bundle.
 * @return array Les détails du bundle.
 */
function get_player_bundle_details(object $bundle_data): array {
	$formatted_bundle = explode("/", (string) $bundle_data->value->string);
	$bundle_name = $formatted_bundle[0];
	$bundle_requirements = get_bundle_requirements($formatted_bundle[2]);
	$bundle_limit = $formatted_bundle[4] ?? count($bundle_requirements);
	
	$bundle_details = [
		"bundle_name" => $bundle_name,
		"requirements" => $bundle_requirements,
		"limit" => $bundle_limit
	];

	return $bundle_details;
}

/**
 * Renvoie les objets requis d'un bundle.
 * 
 * @param string $requirements Les objets requis du bundle.
 * @return array Les objets requis du bundle.
 */
function get_bundle_requirements(string $requirements): array {
	$formatted_requirements = array_chunk(preg_split('/\s+/', $requirements), 3);
	$bundle_requirements = [];
	$item_types = [
		"artifacts"        => sanitize_json_with_version("artifacts"),
		"cooking_recipes"  => sanitize_json_with_version("cooking_recipes"),
		"crafting_recipes" => sanitize_json_with_version("crafting_recipes"),
		"fish"             => sanitize_json_with_version("fish"),
		"minerals"         => sanitize_json_with_version("minerals"),
		"shipped_items"    => sanitize_json_with_version("shipped_items")
	];

	foreach ($formatted_requirements as $item) {
		$item[0] = get_correct_id($item[0]);
		$item[0] = abs($item[0]);
		$item_name = ($item[0] === 1) ? "Gold Coins" : get_item_name_by_id($item[0]);

		if ($item_name === "None") {
			continue;
		}

		$item_type = "additionnal_items";
		foreach ($item_types as $category => $values) {
			if (!in_array($item_name, $values)) {
				continue;
			}
			
			$item_type = $category;
		}

		$bundle_requirement_item = [
			"id" => $item[0],
			"name" => $item_name,
			"quantity" => $item[1],
			"quality" => $item[2],
			"type" => $item_type
		];

		array_push($bundle_requirements, $bundle_requirement_item);
	}

	return $bundle_requirements;
}

/**
 * Vérifie si un objet a été donné dans le bundle.
 * 
 * @param string $name Le nom de l'objet.
 * @param array $donated_items Les objets donnés.
 * @return bool Indique si l'objet a été donné dans le bundle.
 */
function has_been_donated_in_bundle(string $name, array $donated_items): bool {
	$has_been_donated = false;

	foreach ($donated_items as $donated_item) {
		if ($name !== $donated_item["name"]) {
			continue;
		}
		
		$has_been_donated = true;
	}

	return $has_been_donated;
}

/**
 * Renvoie les bundles entiers du joueur.
 * 
 * @return array Les bundles entiers du joueur.
 */
function get_player_bundles(): array {
    $raw_data = $GLOBALS["raw_xml_data"];
	$bundles_index = get_gamelocation_index($raw_data, "bundles");
	$bundles_json = sanitize_json_with_version("bundles", true);
	$bundles_data = $raw_data->bundleData;
	$bundle_arrays = $raw_data->locations->GameLocation[$bundles_index]->bundles;

	foreach ($bundle_arrays->item as $bundle_array) {
		$bundle_id = (int) $bundle_array->key->int;
		$bundle_booleans = (array) $bundle_array->value->ArrayOfBoolean->boolean;

		foreach ($bundles_json as $bundle_room_name => $bundle_room_details) {
			if (!in_array($bundle_id, $bundle_room_details["bundle_ids"])) {
				continue;
			}
			
			$bundle_room = $bundle_room_name;
		}
		
		if (empty($bundle_room)) {
			continue;
		}

		$bundle_data_name = "$bundle_room/$bundle_id";
		$bundle_progress = [
			"room_name" => $bundle_room,
			"id" => $bundle_id,
			"progress"  => $bundle_booleans
		];

		foreach ($bundles_data->item as $bundle_data) {
			if ((string) $bundle_data->key->string !== $bundle_data_name) {
				continue;
			}

			$player_bundles[$bundle_id] = get_player_bundle_progress($bundle_data, $bundle_progress);
		}
	}
	
	ksort($player_bundles);
	return $player_bundles;
}
