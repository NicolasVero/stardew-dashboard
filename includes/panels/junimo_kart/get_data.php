<?php

function get_junimo_kart_leaderboard(): array {
	$data = $GLOBALS["untreated_all_players_data"];
	$all_entries = $data->junimoKartLeaderboards->entries;
	$leaderboard = [];

	foreach($all_entries as $entries) {
		foreach($entries as $entry) {
			$leaderboard[] = [
				"score" => (int) $entry->score->int,
				"name"  => (string) $entry->name->string
			];
		}
	}

	return $leaderboard;
}

function get_junimo_leaderboard(object $junimo_leaderboard): object {
	if(is_object_empty($junimo_leaderboard)) {
		return get_junimo_kart_fake_leaderboard();
	}

	return $junimo_leaderboard;
}

function get_junimo_kart_fake_leaderboard(): object {
    return (object) [
        "NetLeaderboardsEntry" => [
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 50000]
            ],
            (object) [
                "name" => (object) ["string" => "Shane"],
                "score" => (object) ["int" => 25000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 10000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 5000]
            ],
            (object) [
                "name" => (object) ["string" => "Lewis"],
                "score" => (object) ["int" => 250]
            ],
        ],
    ];
}