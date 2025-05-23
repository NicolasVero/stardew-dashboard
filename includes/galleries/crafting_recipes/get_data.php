<?php

/**
 * Récupère les données des recettes d'artisanat débloquées par le joueur.
 * 
 * @return array Les données des recettes d'artisanat débloquées par le joueur.
 */
function get_player_crafting_recipes(): array {
	$player_crafting_recipes = $GLOBALS["current_player_raw_data"]->craftingRecipes;
	$crafting_recipes_json = sanitize_json_with_version("crafting_recipes");
	$crafting_recipes = [];

	foreach ($player_crafting_recipes->item as $recipe) {
		$item_name = format_original_data_string($recipe->key->string);
		$index = array_search($item_name, $crafting_recipes_json);

		$crafting_recipes[$item_name] = [
			"id" => $index,
			"counter" => (int) $recipe->value->int
		];
	}
	
	return $crafting_recipes;
}
