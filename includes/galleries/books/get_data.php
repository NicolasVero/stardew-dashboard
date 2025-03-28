<?php

/**
 * Récupère les données des livres obtenus par le joueur.
 * 
 * @return array Les données des livres obtenus par le joueur.
 */
function get_player_books(): array {
	$player_books = $GLOBALS["untreated_player_data"]->stats->Values;
	return get_player_items_list($player_books, "books");
}
