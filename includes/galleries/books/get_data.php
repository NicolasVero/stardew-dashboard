<?php 

function get_player_books(): array {
	$player_books = $GLOBALS["untreated_player_data"]->stats->Values;
	return get_player_items_list($player_books, "books");
}