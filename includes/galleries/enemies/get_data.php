<?php

/**
 * Récupère les données des ennemis tués par le joueur.
 * 
 * @return array Les données des ennemis tués par le joueur.
 */
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