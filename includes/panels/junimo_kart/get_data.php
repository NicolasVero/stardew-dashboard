<?php

/**
 * Récupère les données du tableau des scores de Junimo Kart.
 *
 * @return array Les données du tableau des scores de Junimo Kart.
 */
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

/**
 * Vérifie si le tableau des scores de Junimo Kart est vide.
 *
 * @param object $junimo_leaderboard Le tableau des scores de Junimo Kart.
 * @return object Le tableau des scores de Junimo Kart.
 */
function get_verified_jk_leaderboard(object $junimo_leaderboard): object {
	if(is_object_empty($junimo_leaderboard)) {
		return get_junimo_kart_fake_leaderboard();
	}

	return $junimo_leaderboard;
}

/**
 * Crée un tableau de scores de Junimo Kart fictif.
 *
 * @return object Le tableau des scores fictif de Junimo Kart.
 */
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
