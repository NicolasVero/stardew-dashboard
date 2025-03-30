<?php 

/**
 * Récupère les données des éléments possédés par un joueur.
 * 
 * @param object $data Les données du joueur.
 * @param string $filename Le nom du fichier JSON.
 * @return array Les données des éléments possédés par un joueur.
 */
function get_player_items_list(object $data, string $filename): array {
	if (is_game_version_older_than_1_6()) {
		return [];
	}

	$items_data = [];

	foreach($data->item as $item) {
		$item_id = format_original_data_string($item->key->string);
		$item_id = get_correct_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if (empty($item_reference)) {
			continue;
		}

		$items_data[$item_reference] = [ "id" => $item_id ];
	}
	
	return $items_data;
}

/**
 * Récupère les noms des joueurs.
 * 
 * @return array Les noms des joueurs.
 */
function get_players_name(): array {
	$players_data = $GLOBALS["all_players_data"];
	$players_names = [];

	for ($i = 0; $i < count($players_data); $i++) {
		array_push($players_names, $players_data[$i]["general"]["name"]);
	}

	return $players_names;
}

/**
 * Tries les personnages en fonction de leur niveau d'amitié de manière décroissante. Puis regarde si un personnage est marié au joueur, dans ce cas, monte le personnage en haut de la liste.
 * 
 * @param array $friendship_data L'array à trier.
 * @return array L'array trié.
 */
function sort_by_friend_level(array $friendship_data): array {
    uasort($friendship_data, function(array $a, array $b): int {
        if ($a['friend_level'] != $b['friend_level']) {
            return $b['friend_level'] - $a['friend_level'];
        }
        
        return $b['points'] - $a['points'];
    });

	$married = array();
    $others = array();

    foreach ($friendship_data as $name => $data) {
        if ($data['status'] === 'Married') {
            $married[$name] = $data;
        } else {
            $others[$name] = $data;
        }
    }

    return $married + $others;
}

/**
 * Indique si un élément est présent dans le courrier d'un joueur.
 * 
 * @param string $element L'élément à vérifier.
 * @return int Indique si l'élément est présent.
 */
function has_element_in_mail(string $element): int {
	$player_data = $GLOBALS["untreated_player_data"] ?? $GLOBALS["untreated_all_players_data"]->player;
    return (in_array($element, (array) $player_data->mailReceived->string)) ? 1 : 0;
}

/**
 * Vérifie si un joueur possède un élèment.
 * 
 * @param object $element L'élément à vérifier.
 * @return int Indique si l'élément est possédé.
 */
function has_element(object $element): int {
    return !empty((array) $element);
}

/**
 * Vérifie si un joueur possède un élèment en fonction de la version du jeu.
 * 
 * @param string $element_older_version L'élément pour les versions antérieures à 1.6.0.
 * @param string $element_newer_version L'élément pour les versions postérieures à 1.6.0.
 * @return int Indique si l'élément est possédé.
 */
function has_element_based_on_version(string $element_older_version, string $element_newer_version): int {
	$player_data = $GLOBALS["untreated_player_data"];

	if (is_game_version_older_than_1_6()) {
		return has_element($player_data->$element_older_version);
	}

	return has_element_in_mail($element_newer_version);
}

/**
 * Génère un texte d'infobulle basé sur les données du joueur et le type de donnée fourni.
 * 
 * @param array $player_data Les données du joueur.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @param string $data_type Le type de donnée.
 * @return string Le texte de l'infobulle.
 */
function get_tooltip_text(array $player_data, string $json_line_name, string $data_type): string {
	if (!array_key_exists($json_line_name, $player_data) || !isset($player_data[$json_line_name])) {
		return __($json_line_name);
	}
	$data_array = $player_data[$json_line_name];

    extract($data_array); //? ?$counter, ?$caught_counter, ?$killed_counter, ?$max_length, ?$description

    switch ($data_type) {
		case "shipped_items" :
			$tooltip_end_text = $counter . __("shipped", SPACE_BEFORE);
			break;
			
        case "farm_animals" : 
			$tooltip_end_text = $counter . __("in your farm", SPACE_BEFORE);
			break;

        case "fish" : 
			$tooltip_end_text = __("caught", SPACE_AFTER) . $caught_counter . __("times", SPACE_BEFORE) . (($max_length > 0) ? " ($max_length " . __("inches") . ")" : "");
			break;

        case "enemies" : 
			$tooltip_end_text = $killed_counter . __("killed", SPACE_BEFORE);
			break;

        case "cooking_recipes" :
			$tooltip_end_text = ($counter === 0) ? __("not cooked yet") : __("cooked", SPACE_AFTER) . (int) $counter . __("times", SPACE_BEFORE);
			break;

		case "crafting_recipes" :
			$tooltip_end_text = ($counter === 0) ? __("not crafted yet") : __("crafted", SPACE_AFTER) . (int) $counter . __("times", SPACE_BEFORE);
			break;

        case "achievements" :
			$tooltip_end_text = __($description);
			break;

        case "artifacts":
        case "minerals":  
			$tooltip_end_text = ($counter === 0) ? __("not given yet") : __("given to museum");
			break;

		case "locations_to_visit" :
        default : 
			return __($json_line_name);
	}

	return __($json_line_name) . ": $tooltip_end_text";
}

/**
 * Indique si un objectif est complété.
 * 
 * @param int $current_counter Le compteur actuel.
 * @param int $limit La limite de l'objectif.
 * @return bool Indique si l'objectif est complété.
 */
function is_objective_completed(int $current_counter, int $limit): bool {
    return ($current_counter >= $limit);
}

/**
 * Récupère le pourcentage de complétion d'un objectif.
 * 
 * @param int $max_amount La quantité maximale de l'objectif.
 * @param int $current_amount La quantité actuelle de l'objectif.
 * @return float Le pourcentage de complétion de l'objectif.
 */
function get_element_completion_percentage(int $max_amount, int $current_amount): float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}

/**
 * Récupère la classe d'un élèment en fonction de la version du jeu.
 * 
 * @param string $version La version du jeu.
 * @return string La classe de l'élément.
 */
function get_version_class(string $version): string {
	return get_game_version_score($GLOBALS["game_version"]) < get_game_version_score($version) ? "newer-version" : "older-version";
}

/**
 * Récupère les classes d'un élément trouvé.
 * 
 * @param array $player_data Les données du joueur.
 * @param string $json_filename Le nom du fichier JSON.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @param bool $is_found Indique si l'élément est trouvé.
 * @return string Les classes de l'élément trouvé.
 */
function get_found_classes(array $player_data, string $json_filename, string $json_line_name, bool $is_found): string {
	$classes = ($is_found) ? "found" : "not-found";
	
	if (in_array($json_filename, ["cooking_recipes", "crafting_recipes", "artifacts", "minerals"])) {
		if ($is_found && $player_data[$json_line_name]["counter"] === 0) {
			$classes .= " unused";
		}
	}
	return $classes;
}
