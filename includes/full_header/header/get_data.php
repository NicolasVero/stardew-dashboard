<?php

/**
 * Génère le texte de l'info-bulle de la météo.
 * 
 * @param string $weather Le type de météo.
 * @return string Le texte de l'info-bulle.
 */
function get_weather_tooltip(string $weather): string {
	return [
		"sun"        => __("It's going to be clear and sunny all day"),
		"rain"       => __("It's going to rain all day tomorrow"),
		"green_rain" => __("Um... There appears to be some kind of... anomalous reading... I... don't know what this means..."),
		"wind"       => __("It's going to be cloudy, with gusts of wind throughout the day"),
		"storm"      => __("Looks like a storm is approaching. Thunder and lightning is expected"),
		"snow"       => __("Expect a few inches of snow tomorrow")
	][$weather] ?? "";
}

/**
 * Renvoie le genre du joueur.
 * 
 * @return string Le genre du joueur.
 */
function get_player_gender(): string {
	$player_data = $GLOBALS["untreated_player_data"];
	$genders = [
		$player_data->gender,
		$player_data->isMale
	];

	foreach($genders as $gender) {
		if(empty($gender)) {
			continue;
		}

		$gender = (string) $gender[0];

		// $gender: (0 / 1) || ("true" / "false") || ("Male" / "Female")
		if(is_numeric($gender)) {
			return ($gender === 0) ? "Male" : "Female";
		} else {
			return ($gender === "true" || $gender === "Male") ? "Male" : "Female";
		}
	}

	return "Neutral";
}

/**
 * Indique si le joueur est marié.
 * 
 * @return bool Indique si le joueur est marié.
 */
function get_is_married(): bool {
	$data = $GLOBALS["untreated_player_data"];
	return isset($data->spouse);
}

/**
 * Renvoie le nom du conjoint du joueur.
 * 
 * @return mixed Le nom du conjoint du joueur, ou null s'il n'est pas marié.
 */
function get_spouse(): mixed {
	$player_data = $GLOBALS["untreated_player_data"];
	return (!empty($player_data->spouse)) ? $player_data->spouse : null;
}

/**
 * Renvoie le niveau d'amélioration de la maison du joueur.
 * 
 * @return int Le niveau d'amélioration de la maison du joueur.
 */
function get_house_upgrade_level(): int {
	return (int) $GLOBALS["untreated_player_data"]->houseUpgradeLevel;
}

/**
 * Renvoie le nombre d'enfants du joueur.
 * 
 * @return array Le nombre d'enfants du joueur.
 */
function get_children_amount(): array {
	$player_id = (int) $GLOBALS["untreated_player_data"]->UniqueMultiplayerID;
	$data = $GLOBALS["untreated_all_players_data"];
	$children_name = [];
	$npc_locations =  [
		"locations.GameLocation.characters.NPC",
		"locations.GameLocation.buildings.Building.indoors.characters.NPC"
	];

	foreach($npc_locations as $npc_location) {
		$npcs = find_xml_tags($data, $npc_location);

		foreach($npcs as $npc) {
			if(!isset($npc->idOfParent)) {
				continue;
			}

			if((int) $npc->idOfParent === $player_id) {
				array_push($children_name, $npc->name);
			}
		}
	}
	
	return $children_name;
}

/**
 * Renvoie le genre du conjoint du joueur.
 * 
 * @param string $spouse Le nom du conjoint du joueur.
 * @return string Le genre du conjoint du joueur.
 */
function get_the_married_person_gender(string $spouse): string {
	$wifes = ["abigail", "emily", "haley", "leah", "maru", "penny"];
	$husbands = ["alex", "elliott", "harvey", "sam", "sebastian", "shane"];

	if(in_array(strtolower($spouse), $wifes)) {
		return "wife";
	}

	if(in_array(strtolower($spouse), $husbands)) {
		return "husband";
	}

	return "spouse";
}

/**
 * Renvoie la météo actuelle.
 * 
 * @param string $weather_location L'emplacement de la météo.
 * @return string La météo actuelle.
 */
function get_weather(string $weather_location = "Default"): string {
    $data = $GLOBALS["untreated_all_players_data"];
    $locations = $data->locationWeather;
	$weather_conditions = [
		'Festival' => 'Festival',
		'isRaining' => 'rain',
		'isSnowing' => 'snow', 
		'isLightning' => 'storm',
		'isGreenRain' => 'green_rain'
	];

    foreach($locations as $complex_location) {
		foreach($complex_location as $location) {
			if($location->key->string !== $weather_location) {
				continue;
			}

			$weather_data = $location->value->LocationWeather;
			foreach($weather_conditions as $condition => $result) {
				if((string) $weather_data->$condition->string === 'true') {
					return $result;
				}
			}

			return format_text_for_file((string)$weather_data->weather->string);
        }
    }

	return "sun";
}

/**
 * Renvoie le niveau du joueur.
 * 
 * @return string Le niveau du joueur.
 */
function get_farmer_level(): string {
	$player_data = $GLOBALS["untreated_player_data"];
    $level = (get_total_skills_level() + $player_data->luckLevel) / 2;
    $level_names = [
        "Newcomer",
        "Greenhorn",
        "Bumpkin",
        "Cowpoke",
        "Farmhand",
        "Tiller",
        "Smallholder",
        "Sodbuster",
        "Farmboy",
        "Granger",
        "Planter",
        "Rancher",
        "Farmer",
        "Agriculturist",
        "Cropmaster",
        "Farm King"
    ];

    return $level_names[floor($level / 2)];
}

/**
 * Renvoie le score du grand-père du joueur.
 * 
 * @return int Le score du grand-père du joueur.
 */
function get_grandpa_score(): int {
    $data = $GLOBALS["untreated_player_data"];
    $grandpa_points = 0;

    $money_earned_goals = [
        ["goal" => 50000, "points" => 1],
        ["goal" => 100000, "points" => 1],
        ["goal" => 200000, "points" => 1],
        ["goal" => 300000, "points" => 1],
        ["goal" => 500000, "points" => 1],
        ["goal" => 1000000, "points" => 2]
    ];

    $skill_goals = [
        ["goal" => 30, "points" => 1],
        ["goal" => 50, "points" => 1]
    ];

    $achievement_ids = [5, 26, 34];
    $friendship_goals = [5, 10];
    $cc_rooms = [
        "ccBoilerRoom", "ccCraftsRoom", "ccPantry", 
        "ccFishTank", "ccVault", "ccBulletin"
    ];

    $total_money_earned = $data->totalMoneyEarned;
    foreach($money_earned_goals as $goal_data) {
        if($total_money_earned > $goal_data["goal"]) {
            $grandpa_points += $goal_data["points"];
        }
    }

    $total_skills_level = get_total_skills_level();
    foreach($skill_goals as $goal_data) {
        if($total_skills_level > $goal_data["goal"]) {
            $grandpa_points += $goal_data["points"];
        }
    }

    foreach($achievement_ids as $achievement_id) {
        if(does_player_have_achievement($data->achievements, $achievement_id)) {
            $grandpa_points++;
        }
    }

    $house_level = get_house_upgrade_level($data);
    $is_married = get_is_married();
    if($house_level >= 2 && $is_married) {
        $grandpa_points++;
    }

    $friendships = get_player_friendship_data($data->friendshipData);
    $friendship_count = 0;
    foreach($friendships as $friendship) {
        if($friendship["friend_level"] >= 8) {
            $friendship_count++;
        }
    }

    foreach($friendship_goals as $goal) {
        if($friendship_count >= $goal) {
            $grandpa_points++;
        }
    }

    if(get_pet_frienship_points() >= 999) {
        $grandpa_points++;
    }

    $cc_completed = array_reduce($cc_rooms, function(bool $completed, string $room): bool {
        return $completed && has_element_in_mail($room);
    }, true);

    if($cc_completed) {
        $grandpa_points++;
    }

    if(in_array(191393, (array)$data->eventsSeen->int)) {
        $grandpa_points += 2;
    }

	$player_unlockables = get_player_unlockables();
    if($player_unlockables["skull_key"]) {
        $grandpa_points++;
    }

    if($player_unlockables["rusty_key"]) {
        $grandpa_points++;
    }

    return $grandpa_points;
}

/**
 * Renvoie le nombre de bougies allumées.
 * 
 * @param int $grandpa_score Le score du grand-père du joueur.
 * @return int Le nombre de bougies allumées.
 */
function get_candles_lit(int $grandpa_score): int {
	if($grandpa_score <= 3) {
        return 1;
    }
	
	if($grandpa_score > 3 && $grandpa_score <= 7) {
        return 2;
    }
	
	if($grandpa_score > 7 && $grandpa_score <= 11) {
        return 3;
    }
	
	return 4;
}

/**
 * Récupère les éléments de perfection pour la version du jeu spécifiée.
 * 
 * @return array Les éléments de perfection.
 */
function get_perfection_max_elements(): array {
	$game_version = substr($GLOBALS["game_version"], 0, 3);
	if((float) $game_version < 1.5) {
		$game_version = "1.5";
	}
	
	return $GLOBALS["json"]["perfection_elements"][$game_version];
}

/**
 * Calcule le pourcentage de complétion d'un élément de perfection.
 * 
 * @return array Les éléments de perfection.
 */
function get_perfection_elements(): array {
	$general_data = $GLOBALS["host_player_data"]["general"];
	$perfection_elements = get_perfection_max_elements();

	$highest_items_shipped 		= get_highest_count_for_category("shipped_items")["highest_count"];
	$highest_farmer_level 		= get_highest_count_for_category("farmer_level")["highest_count"];
	$highest_fish_caught 		= get_highest_count_for_category("fish_caught")["highest_count"];
	$highest_cooking_recipes 	= get_highest_count_for_category("cooking_recipes")["highest_count"];
	$highest_crafting_recipes 	= get_highest_count_for_category("crafting_recipes")["highest_count"];
	$highest_friendship 		= get_player_with_highest_friendships()["highest_count"];

	return [
		"Golden Walnuts found"		=> get_element_completion_percentage($perfection_elements["golden_walnuts"], (int) $general_data["golden_walnuts"]) * 5,
		"Crafting Recipes Made"		=> get_element_completion_percentage($perfection_elements["crafting_recipes"], $highest_crafting_recipes) * 10,
		"Cooking Recipes Made"		=> get_element_completion_percentage($perfection_elements["cooking_recipes"], $highest_cooking_recipes) * 10,
		"Produce & Forage Shipped"	=> get_element_completion_percentage($perfection_elements["shipped_items"], $highest_items_shipped) * 15,
		"Obelisks on Farm"			=> get_element_completion_percentage($perfection_elements["obelisks"], get_amount_obelisk_on_map()) * 4 ,
		"Farmer Level"				=> get_element_completion_percentage($perfection_elements["farmer_level"], $highest_farmer_level) * 5 ,
		"Fish Caught"				=> get_element_completion_percentage($perfection_elements["fish_caught"], $highest_fish_caught) * 10,
		"Great Friends"				=> get_element_completion_percentage($perfection_elements["friendship"], $highest_friendship) * 11,
		"Monster Slayer Hero"		=> get_element_completion_percentage(1, (int) has_players_done_monster_slayer_hero()) * 10,
		"Found All Stardrops"		=> get_element_completion_percentage(1, (int) has_any_player_gotten_all_stardrops()) * 10,
		"Golden Clock on Farm"		=> get_element_completion_percentage(1, (int) is_golden_clock_on_farm()) * 10
	];
}

/**
 * Calcule le pourcentage de complétion d'un élément de perfection.
 * 
 * @return string Le pourcentage de complétion.
 */
function get_perfection_percentage(): string {
	$untreated_data = $GLOBALS["untreated_all_players_data"];
	if((string) $untreated_data->farmPerfect === "true") {
		return "100";
	}

	$perfection_elements = get_perfection_elements();
	$percentage = 0;
	foreach($perfection_elements as $element_percent) {
		$percentage += $element_percent;
	}

	return (string) round($percentage);
}

/**
 * Récupère le joueur ayant le plus grand nombre d'éléments pour une catégorie donnée.
 * 
 * @param string $category La catégorie à vérifier.
 * @return array Les données du joueur ayant le plus grand nombre d'éléments.
 */
function get_highest_count_for_category(string $category): array {
	$total_players = get_number_of_player();
	$all_data = $GLOBALS["all_players_data"];
	$highest_player = 0;
	$max_elements = 0;

	$exceptions_recipes = [
		"cooking_recipes",
		"crafting_recipes"
	];

	$exceptions_level = [
		"farmer_level"
	];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(in_array($category, $exceptions_recipes)) {
			$filtered_elements = array_filter($all_data[$current_player][$category], function(mixed $item): bool {
				return $item["counter"] > 0;
			});
			$amount_elements = count($filtered_elements);
		} else if(in_array($category, $exceptions_level)) {
			$level_category = $all_data[$current_player]["levels"];
			$amount_elements = 0;
			
			foreach($level_category as $level) {
				$amount_elements += $level;
			}
		} else {
			$amount_elements = count($all_data[$current_player][$category]);	
		}

		if($max_elements < $amount_elements) {
			$max_elements = $amount_elements;
			$highest_player = $current_player;
		}
	}

	$perfection_max = get_perfection_max_elements()[$category];
	$max_elements = min($max_elements, $perfection_max);

	return [
		"player_id" => $highest_player,
		"highest_count" => $max_elements
	];
}

/**
 * Récupère le joueur ayant le plus grand nombre d'amis.
 * 
 * @return array Les données du joueur ayant le plus grand nombre d'amis.
 */
function get_player_with_highest_friendships(): array {
	$total_players = get_number_of_player();
    $marriables_npc = sanitize_json_with_version("marriables");
	$all_data = $GLOBALS["all_players_data"];
	$highest_player = 0;
	$max_elements = 0;

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$friendships = $all_data[$current_player]["friendship"];
		$friend_counter = 0;

		foreach($friendships as $friendship_name => $friendship) {
			extract($friendship); //? $id, $points, $friend_level, $birthday, $status, $week_gifts

			$can_be_married = in_array($friendship_name, $marriables_npc) && $status === "Friendly";

			if(($can_be_married && $friend_level >= 8) || (!$can_be_married && $friend_level >= 10)) {
				$friend_counter++;
			}
		}

		if($friend_counter > $max_elements) $max_elements = $friend_counter;
	}

	$perfection_max = get_perfection_max_elements()["friendship"];
	$max_elements = min($max_elements, $perfection_max);

	return [
		"player_id" => $highest_player,
		"highest_count" => $max_elements
	];
}

/**
 * Vérifie si l'un des joueurs a trouvé toutes les stardrops.
 * 
 * @return bool Indique si l'un des joueurs a trouvé toutes les stardrops.
 */
function has_any_player_gotten_all_stardrops(): bool {
	$total_players = get_number_of_player();
	$all_data = $GLOBALS["all_players_data"];

	for($current_player = 0; $current_player < $total_players; $current_player++) {
		$stardrops_founds = $all_data[$current_player]["general"]["stardrops_found"];

		if($stardrops_founds === 7) {
			return true;
		}
	}

	return false;
}

/**
 * Renvoie le texte de l'info-bulle des enfants.
 * 
 * @param string $spouse Le nom du conjoint du joueur.
 * @param array $children Les noms des enfants du joueur.
 * @return string Le texte de l'info-bulle des enfants.
 */
function get_child_tooltip(string $spouse, array $children): string {
	$gender = get_the_married_person_gender($spouse);
	$children_count = count($children);
	$children_names = ($children_count === 1) ? $children[0] : implode(" " . __("and") . " ", $children);
	$nombre = ($children_count > 1) ? __("children") : __("child");

	if($children_count === 0) {   
        return __("With your") . " " . __($gender) . " $spouse, " . __("haven't yet had a child");
    }

	return __("With your") . " " . __($gender) . " $spouse, " . __("you had") . " $children_count $nombre : $children_names";
}

/**
 * Renvoie l'animal de compagnie du joueur.
 * 
 * @return array Les données de l'animal de compagnie du joueur.
 */
function get_player_pet(): array {
	$player_data = $GLOBALS["untreated_player_data"];
	$breed = (int) $player_data->whichPetBreed;
	$type = (is_game_version_older_than_1_6()) ?
		(((string) $player_data->catPerson === "true") ? "cat" : "dog")
		:
		lcfirst((string) $player_data->whichPetType);

	return [
		"type"  => $type,
		"breed" => $breed
	];
}

/**
 * Renvoie le nombre de stardrops trouvés par le joueur.
 * 
 * @return int Le nombre de stardrops trouvés par le joueur.
 */
function get_player_stardrops_found(): int {
	$player_stamina = (int) $GLOBALS["untreated_player_data"]->maxStamina;
	$min_stamina = 270;
	$stamina_per_stardrop = 34;
	return ($player_stamina - $min_stamina) / $stamina_per_stardrop;
}

/**
 * Renvoie le nombre d'obélisques sur la carte.
 * 
 * @return int Le nombre d'obélisques sur la carte.
 */
function get_amount_obelisk_on_map(): int {
	$obelisk_count = 0;
	$obelisk_names = get_obelisk_names();
	$data = $GLOBALS["untreated_all_players_data"];
	$buildings = find_xml_tags($data, 'locations.GameLocation.buildings.Building');

	foreach($buildings as $building) {
		if(in_array((string) $building->buildingType, $obelisk_names)) {
            $obelisk_count++;
        }
	}

	return $obelisk_count;
}

/**
 * Renvoie les noms des obélisques.
 * 
 * @return array Les noms des obélisques.
 */
function get_obelisk_names() :array {
	return [
		"Earth Obelisk",
		"Water Obelisk",
		"Island Obelisk",
		"Desert Obelisk",
	];
}

/**
 * Indique si l'horloge dorée est sur la ferme.
 * 
 * @return bool Indique si l'horloge dorée est sur la ferme.
 */
function is_golden_clock_on_farm(): bool {	
	$data = $GLOBALS["untreated_all_players_data"];
	$buildings = find_xml_tags($data, 'locations.GameLocation.buildings.Building');

	foreach($buildings as $building) {
		if((string) $building->buildingType === "Gold Clock") {
            return true;
        }
	}

	return false;
}

/**
 * Renvoie les points d'amitié de l'animal de compagnie.
 * 
 * @return int Les points d'amitié de l'animal de compagnie.
 */
function get_pet_frienship_points(): int {
	$data = $GLOBALS["untreated_all_players_data"];
	$npcs = find_xml_tags($data, 'locations.GameLocation.characters.NPC');

	foreach($npcs as $npc) {
		if(!isset($npc->petType) && !isset($npc->whichBreed)) {
			continue;
		}
		
		return (int) $npc->friendshipTowardFarmer;
	}
	
	return 0;
}