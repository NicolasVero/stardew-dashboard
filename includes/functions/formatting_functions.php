<?php 

/**
 * Formate et retourne un texte pour un fichier.
 * 
 * @param string $string Le texte à formater.
 * @return string Le texte formaté.
 */
function format_text_for_file(string $string): string {
    $search  = [" ", "'", "(", ")", ",", ".", ":"];
    $replace = ["_", "" , "" , "" , "" , "" , "" ];
    $string = str_replace($search, $replace, $string);
    $string = strtolower($string);

    if (substr($string, -1) === "_") {
        $string = substr($string, 0, -1);
    }

    return $string;
}

/**
 * Formate et retourne un texte pour la gestion des données.
 * 
 * @param string $data Le texte à formater.
 * @return string Le texte formaté.
 */
function format_original_data_string(string $data): string {
    return str_replace("(O)", "", $data);
}

/**
 * Convertit une chaîne de caractères en octets.
 * 
 * @param string $size La taille à convertir.
 * @param string $use L'unité à utiliser.
 * @return int La taille en octets.
 */
function in_bytes_conversion(string $size, string $use = "local"): int {
    $unit_to_power = ($use === "local") 
		? ["o"  => 0, "Ko" => 1, "Mo" => 2, "Go" => 3]
		: ["K" => 1, "M" => 2, "G" => 3];

    preg_match("/(\d+)([a-zA-Z]+)/", $size, $matches);
    
    $value = (int) $matches[1];
    $unite = $matches[2];
    
    return $value * pow(1024, $unit_to_power[$unite]);
}

/**
 * Formate et retourne la date du jeu sous forme de chaîne ou de tableau.
 * 
 * @param bool $display_date Indique si la date doit être retournée pour affichage ou non.
 * @return mixed La date formatée.
 */
function get_formatted_date(bool $display_date = true): mixed {
	$data = $GLOBALS["untreated_player_data"];
    $day    = $data->dayOfMonthForSaveGame;
    $season = ["spring", "summer", "fall", "winter"][$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    if ($display_date) {
		return __("Day") . " $day " . __("of $season") . ", " . __("Year") . " $year";
	}

    return [
        "day" => $day,
        "season" => $season,
        "year" => $year
	];
}

/**
 * Formate et retourne un nombre sous forme de chaîne en fonction des normes linguistiques.
 * 
 * @param int $number Le nombre à formater.
 * @param string $lang La langue du site.
 * @return string Le nombre formaté.
 */
function format_number(int $number, string $lang = "en"): string {
	if ($lang === "fr") {
		return number_format($number, 0, ",", " ");
	}

	return number_format($number);
} 
