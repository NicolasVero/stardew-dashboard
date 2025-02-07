<?php

/**
 * Vérifie si le joueur a effectué l'ojectif "Monster Slayer Hero".
 * 
 * @return bool Indique si le joueur a effectué l'ojectif "Monster Slayer Hero".
 */
function has_players_done_monster_slayer_hero(): bool {
	$total_players = get_number_of_player();
	
	for($current_player = 0; $current_player < $total_players; $current_player++) {
		if(get_player_adventurers_guild_data($current_player)["is_all_completed"]) {
			return true;
		}
	}

	return false;
}

/**
 * Récupère les données de l'objectif "Monster Slayer Hero" pour un joueur.
 * 
 * @param int $player_id L'identifiant du joueur.
 * @return array Les données de l'objectif "Monster Slayer Hero".
 */
function get_player_adventurers_guild_data(int $player_id): array {
	$categories = get_all_adventurers_guild_categories();
	$enemies_killed = $GLOBALS["all_players_data"][$player_id]["enemies_killed"];
	$adventurers_guild_data = [];

	foreach($categories as $monsters_name => $monster_data) {
		$counter = 0;
		extract($monster_data); //? $target_name, $ids, $limit, $reward

		foreach($enemies_killed as $enemy_killed) {
			if(in_array($enemy_killed["id"], $ids)) {
				$counter += $enemy_killed["killed_counter"];
			}
		}

		$adventurers_guild_data[$monsters_name] = [
			"target"		=> $target_name,
			"counter"		=> $counter,
			"limit"			=> $limit,
			"reward"		=> $reward,
			"is_completed"	=> is_objective_completed($counter, $limit)
		];
	}

    $adventurers_guild_data["is_all_completed"] = are_all_adventurers_guild_categories_completed($adventurers_guild_data);

	return $adventurers_guild_data;
}

/**
 * Vérifie si le joueur a rempli toutes les catégories de l'objectif "Monster Slayer Hero".
 * 
 * @param array $adventurers_guild_data Les données de l'objectif "Monster Slayer Hero".
 * @return bool Indique si le joueur a rempli toutes les catégories de l'objectif "Monster Slayer Hero".
 */
function are_all_adventurers_guild_categories_completed(array $adventurers_guild_data): bool {
    $counter = 0;
    foreach($adventurers_guild_data as $data) {
        if($data["is_completed"]) {	
			$counter++;
		}
    }

    return $counter === count($adventurers_guild_data);
}

/**
 * Récupère toutes les catégories de l'objectif "Monster Slayer Hero".
 * 
 * @return array Les catégories de l'objectif "Monster Slayer Hero".
 */
function get_all_adventurers_guild_categories(): array {
	return $GLOBALS["json"]["adventurer's_guild_goals"];
}