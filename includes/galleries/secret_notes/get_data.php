<?php

/**
 * Récupère les données des notes secrètes débloquées par le joueur.
 * 
 * @return array Les données des notes secrètes débloquées par le joueur.
 */
function get_player_secret_notes(): array {
	$player_secret_notes = (array) $GLOBALS["current_player_raw_data"]->secretNotesSeen->int;
	sort($player_secret_notes);
	$secret_notes = [];

	foreach ($player_secret_notes as $secret_note) {
		$secret_note_name = find_reference_in_json($secret_note, "secret_notes");
		$secret_notes[$secret_note_name] = [
			"id" => $secret_note
		];
	}

	return $secret_notes;
}
