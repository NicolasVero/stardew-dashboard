<?php

/**
 * Récupère les données des recettes de cuisine débloquées par le joueur.
 * 
 * @return array Les données des recettes de cuisine débloquées par le joueur.
 */
function get_player_cooking_recipes(): array {
	$player_recipes = $GLOBALS["untreated_player_data"]->cookingRecipes;
	$player_recipes_cooked = $GLOBALS["untreated_player_data"]->recipesCooked;
	$cooking_recipes_json = sanitize_json_with_version("cooking_recipes");
	$cooking_recipes_data = [];

	$has_ever_cooked = (empty((array) $player_recipes_cooked)) ? false : true;

	foreach($player_recipes->item as $recipe) {
		$item_name = format_original_data_string($recipe->key->string);
		$index = array_search($item_name, $cooking_recipes_json);      

		if ($has_ever_cooked) {
			foreach($player_recipes_cooked->item as $recipe_cooked) {
				$recipe_id = ((is_game_version_older_than_1_6())) ? (int) $recipe_cooked->key->int : $recipe_cooked->key->string;
				$recipe_id = get_correct_id($recipe_id);

				if ($recipe_id === $index) {
					$cooking_recipes_data[$item_name] = [
						"id"      => $recipe_id,
						"counter" => (int) $recipe_cooked->value->int
					];
					break;
				}
			}

			if (isset($cooking_recipes_data[$item_name])) {
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
