<?php

/**
 * Vérifie si la sauvegarde est plus ancienne que 1.6.0.
 * 
 * @return bool Indique si la sauvegarde est plus ancienne ou non.
 */
function is_game_version_older_than_1_6(): bool {
	return get_game_version_score($GLOBALS["game_version"]) < get_game_version_score("1.6.0");
}

/**
 * Récupère le temps de jeu du joueur.
 * 
 * @return string Le temps de jeu du joueur.
 */
function get_game_duration(): string {
	$player_game_duration = (int) $GLOBALS["untreated_player_data"]->millisecondsPlayed;

    $total_seconds = intdiv($player_game_duration, 1000);
    $total_minutes = intdiv($total_seconds, 60);

    $seconds = $total_seconds % 60;
    $minutes = $total_minutes % 60;
    $hours = intdiv($total_minutes, 60);
	
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

/**
 * Récupère le nombre de joueurs dans la partie.
 * 
 * @return int Le nombre de joueurs.
 */
function get_number_of_player(): int {
    return $GLOBALS["number_of_players"];
}

/**
 * Récupère le nombre de jours de jeu.
 * 
 * @return int Le nombre de jours de jeu.
 */
function get_number_of_days_ingame(): int {
	$data = $GLOBALS["untreated_player_data"];
    return ((($data->dayOfMonthForSaveGame - 1)) + ($data->seasonForSaveGame * 28) + (($data->yearForSaveGame - 1) * 112));
}

/**
 * Récupère le score de la version du jeu.
 * 
 * @param string $version La version du jeu.
 * @return int Le score de la version du jeu.
 */
function get_game_version_score(string $version): int {
	$version_numbers = explode(".", $version);

	while (count($version_numbers) < 3) {
        $version_numbers[] = 0;
    }

	$version_numbers = array_reverse($version_numbers);
	$score = 0;

	for ($i = 0; $i < count($version_numbers); $i++) {
        $score += $version_numbers[$i] * pow(1000, $i); 
    }

	return (int) $score;
}

/**
 * Vérifie si la date cherchée est la même que celle du jour actuel.
 * 
 * @param string $date La date à vérifier.
 * @return bool Indique si la date est la même que celle du jour actuel.
 */
function is_this_the_same_day(string $date): bool {
    extract(get_formatted_date(false)); //? $day, $season, $year
    return $date === "$day/$season";
}

/**
 * Vérifie si la partie est en mode solo.
 * 
 * @return bool Indique si la partie est en mode solo.
 */
function is_game_singleplayer(): bool {
	return get_number_of_player() === 1;
}
