<?php 

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
