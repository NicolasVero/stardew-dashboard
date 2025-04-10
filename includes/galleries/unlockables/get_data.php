<?php

/**
 * Récupère les données des éléments débloqués par le joueur.
 * 
 * @return array Les données des éléments débloqués par le joueur.
 */
function get_player_unlockables(): array {
	$player_data = $GLOBALS["untreated_player_data"];
	$player_unlockables = [];
	$unlockables_details = get_unlockables_details();

	foreach ($unlockables_details as $unlockable_name => $unlockable_details) {
		extract($unlockable_details); //? $type, $element_name

		switch ($type) {
			case "mail" :
				$player_unlockables[$unlockable_name] = has_element_in_mail($element_name);
				break;
			case "version" :
				$player_unlockables[$unlockable_name] = has_element_based_on_version($older_element, $newer_element);
				break;
			case "event" :
				$player_unlockables[$unlockable_name] = (int) in_array($event_id, (array) $player_data->eventsSeen->int);
				break;
			case "element_host" :
				if (is_game_version_older_than_1_6()) {
					$player_unlockables[$unlockable_name] = has_element($player_data->$older_element);
				} else {
					$player_unlockables[$unlockable_name] = has_unlockable_element_based_on_host($unlockable_name, $newer_element);
				}
				break;
		}
	}

	return $player_unlockables;
}

/**
 * Récupère les détails des éléments débloqués.
 * 
 * @return array Les détails des éléments débloqués.
 */
function get_unlockables_details(): array {
	return [
		"forest_magic" => [
			"type" => "mail",
			"element_name" => "canReadJunimoText"
		],
		"dwarvish_translation_guide" => [
			"type" => "element_host",
			"older_element" => "canUnderstandDwarves",
			"newer_element" => "HasDwarvishTranslationGuide"
		],
		"rusty_key" => [
			"type" => "element_host",
			"older_element" => "hasRustyKey",
			"newer_version" => "HasRustyKey"
		],
		"club_card" => [
			"type" => "version",
			"older_element" => "hasClubCard",
			"newer_element" => "HasClubCard"
		],
		"special_charm" => [
			"type" => "version",
			"older_element" => "hasSpecialCharm",
			"newer_element" => "HasSpecialCharm"
		],
		"skull_key" => [
			"type" => "element_host",
			"older_element" => "hasSkullKey",
			"newer_element" => "HasSkullKey"
		],
		"magnifying_glass" => [
			"type" => "version",
			"older_element" => "hasMagnifyingGlass",
			"newer_element" => "HasMagnifyingGlass"
		],
		"dark_talisman" => [
			"type" => "version",
			"older_element" => "hasDarkTalisman",
			"newer_element" => "HasDarkTalisman"
		],
		"magic_ink" => [
			"type" => "version",
			"older_element" => "hasMagicInk",
			"newer_element" => "hasPickedUpMagicInk"
		],
		"bears_knowledge" => [
			"type" => "event",
			"event_id" => 2120303,
		],
		"spring_onion_mastery" => [
			"type" => "event",
			"event_id" => 3910979,
		],
		"town_key" => [
			"type" => "version",
			"older_element" => "HasTownKey",
			"newer_element" => "HasTownKey",
		]
	];
}

/**
 * Vérifie si le joueur hôte a un élément dans ses mails.
 * 
 * @param string $element Le nom de l'élément.
 * @param string $element_newer_version Le nom de l'élément dans la version 1.6.0.
 * @return int Indique si le joueur hôte a l'élément ou non.
 */
function has_unlockable_element_based_on_host(string $element, string $element_newer_version): int {
	if (isset($GLOBALS["host_player_data"])) {
		return does_host_has_unlockable_element($element);
	}
	
	return has_element_in_mail($element_newer_version);
}

/**
 * Vérifie si le joueur hôte a un élément débloqué.
 * 
 * @param string $element Le nom de l'élément.
 * @return int Indique si le joueur hôte a l'élément ou non.
 */
function does_host_has_unlockable_element(string $element): int {
	return ($GLOBALS["host_player_data"]["unlockables"][$element]["is_found"]);
}

/**
 * Récupère la liste des éléments débloqués par le joueur.
 * 
 * @return array La liste des éléments débloqués par le joueur.
 */
function get_player_unlockables_list(): array {
	$unlockables_json = sanitize_json_with_version("unlockables");
	$unlockables = get_player_unlockables();

	foreach ($unlockables_json as $unlockable_id => $unlockable_name) {
		$formatted_name = format_text_for_file($unlockable_name);
		$unlockables[$formatted_name] = [
			"id" => $unlockable_id,
			"is_found" => $unlockables[$formatted_name]
		];
	}

	return $unlockables;
}