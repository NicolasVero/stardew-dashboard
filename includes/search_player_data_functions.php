<?php 



function get_player_achievements(): array {
    $player_achievements = $GLOBALS["untreated_player_data"]->achievements;
	$achievements_data = [];
	
	foreach($player_achievements->int as $achievement) {
		$achievement = find_reference_in_json((int) $achievement, "achievements_details");
		extract($achievement);

		$achievements_data[$title] = [ "description" => $description ];
	}
	
	return $achievements_data;
}

function does_player_have_achievement(object $achievements, int $achievement_id): bool {
	foreach($achievements->int as $achievement) {
		if($achievement_id !== $achievement) {
			continue;
		}

		return true;
	}

	return false;
}



function get_player_shipped_items(): array {
	$player_items = $GLOBALS["untreated_player_data"]->basicShipped;
	$shipped_items_data = [];

	foreach($player_items->item as $item) {
		$item_id = (is_game_older_than_1_6()) ? $item->key->int : $item->key->string;
		$item_id = formate_original_data_string($item_id);
		$item_id = get_correct_id($item_id);

		$shipped_items_reference = find_reference_in_json($item_id, "shipped_items");

		if(empty($shipped_items_reference)) {
			continue;
		}

		$shipped_items_data[$shipped_items_reference] = [ "id" => $item_id ];
	}
	
	return $shipped_items_data;
}

function get_player_skills_data(): array {
	$player_skills = (array) $GLOBALS["untreated_player_data"]->professions->int;
	$json_skills = sanitize_json_with_version("skills");
	$skills_data = [];

	foreach($json_skills as $key => $skill) {
		if(!in_array($key, $player_skills)) {
			continue;
		}

		$skills_data[] = $json_skills[$key];
	}

	return $skills_data;
}

function get_player_enemies_killed_data(): array { 
	$player_enemies_killed = $GLOBALS["untreated_player_data"]->stats;
	$enemies_data = [];
	
	foreach($player_enemies_killed->specificMonstersKilled->item as $enemy_killed) {
		$enemies_data[(string) $enemy_killed->key->string] = [
			"id"             => get_custom_id((string) $enemy_killed->key->string),
			"killed_counter" => (int) $enemy_killed->value->int
		];
	}

	return $enemies_data;
}

function get_player_books(): array {
	$player_books = $GLOBALS["untreated_player_data"]->stats->Values;
	return get_player_items_list($player_books, "books");
}

function get_player_masteries(): array {
	$player_masteries = $GLOBALS["untreated_player_data"]->stats->Values;
	return get_player_items_list($player_masteries, "masteries");
}

function get_player_items_list(object $data, string $filename): array {
	if(is_game_older_than_1_6()) {
		return [];
	}

	$items_data = [];

	foreach($data->item as $item) {
		$item_id = formate_original_data_string($item->key->string);
		$item_id = get_correct_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if(empty($item_reference)) {
			continue;
		}

		$items_data[$item_reference] = [ "id" => $item_id ];
	}
	
	return $items_data;
}







function get_player_crafting_recipes(): array {
	$player_crafting_recipes = $GLOBALS["untreated_player_data"]->craftingRecipes;
	$crafting_recipes_json = sanitize_json_with_version("crafting_recipes");
	$crafting_recipes_data = [];

	foreach($player_crafting_recipes->item as $recipe) {
		
		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $crafting_recipes_json);

		$crafting_recipes_data[$item_name] = [
			"id" => $index,
			"counter" => (int) $recipe->value->int
		];
	}
	
	return $crafting_recipes_data;
}

function get_player_cooking_recipes(): array {
	$player_recipes = $GLOBALS["untreated_player_data"]->cookingRecipes;
	$player_recipes_cooked = $GLOBALS["untreated_player_data"]->recipesCooked;
	$cooking_recipes_json = sanitize_json_with_version("cooking_recipes");
	$cooking_recipes_data = [];

	$has_ever_cooked = (empty((array) $player_recipes_cooked)) ? false : true;

	foreach($player_recipes->item as $recipe) {
		$item_name = formate_original_data_string($recipe->key->string);
		$index = array_search($item_name, $cooking_recipes_json);      

		if($has_ever_cooked) {
			foreach($player_recipes_cooked->item as $recipe_cooked) {
				$recipe_id = ((is_game_older_than_1_6())) ? (int) $recipe_cooked->key->int : $recipe_cooked->key->string;
				$recipe_id = get_correct_id($recipe_id);

				if($recipe_id === $index) {
					$cooking_recipes_data[$item_name] = [
						"id"      => $recipe_id,
						"counter" => (int) $recipe_cooked->value->int
					];
					break;
				}
			}

			if(isset($cooking_recipes_data[$item_name])) {
				continue;
			}
		}
		
		$cooking_recipes_data[$item_name] = [
			"id"      => $index,
			"counter" => 0
		];

	}
	
	return $cooking_recipes_data;
}

function get_player_artifacts(): array {
	$player_artifacts = $GLOBALS["untreated_player_data"]->archaeologyFound;
    $general_data = $GLOBALS["untreated_all_players_data"];
	$artifacts_data = [];

	foreach($player_artifacts->item as $artifact) {

		$artifact_id = ((is_game_older_than_1_6())) ? $artifact->key->int : $artifact->key->string;
		$artifact_id = formate_original_data_string((string) $artifact_id);
		$artifact_id = get_correct_id($artifact_id);

		$artifacts_reference = find_reference_in_json($artifact_id, "artifacts");
		$museum_index = get_gamelocation_index($general_data, "museumPieces");

		if(!empty($artifacts_reference)) {
			$artifacts_data[$artifacts_reference] = [
				"id"      => $artifact_id,
				"counter" => is_given_to_museum($artifact_id, $general_data, $museum_index)
			];
		}
	}
	
	return $artifacts_data;
}

function get_player_minerals(): array {
	$player_minerals = $GLOBALS["untreated_player_data"]->mineralsFound;
    $general_data = $GLOBALS["untreated_all_players_data"];
	$minerals_data = [];

	foreach($player_minerals->item as $mineral) {
		$mineral_id = ((is_game_older_than_1_6())) ? $mineral->key->int : $mineral->key->string;
		$mineral_id = formate_original_data_string((string) $mineral_id);
		$mineral_id = get_correct_id($mineral_id);
		
		$minerals_reference = find_reference_in_json($mineral_id, "minerals");
		$museum_index = get_gamelocation_index($general_data, "museumPieces");

		if(!empty($minerals_reference)) {
			$minerals_data[$minerals_reference] = [
				"id"      => $mineral_id,
				"counter" => is_given_to_museum($mineral_id, $general_data, $museum_index)
			];
		}
	}
	
	return $minerals_data;
}








function get_player_secret_notes(): array {
	$player_secret_notes = $GLOBALS["untreated_player_data"]->secretNotesSeen;
	$player_secret_notes = (array) $player_secret_notes->int;
	sort($player_secret_notes);
	$all_secret_notes = [];

	foreach($player_secret_notes as $secret_note) {
		$secret_note_name = find_reference_in_json($secret_note, "secret_notes");
		$all_secret_notes[$secret_note_name] = [
			"id" => $secret_note
		];
	}

	return $all_secret_notes;
}







