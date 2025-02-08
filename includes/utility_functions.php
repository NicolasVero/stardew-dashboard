<?php

/**
 * Traduit un texte si une traduction est disponible et ajoute des espaces en fonction de l'option spécifiée.
 *
 * @param string $text Le texte à traduire.
 * @param int $option L'option de l'espacement du texte.
 * @return string Le texte traduit.
 */
function __(string $text, int $option = SPACE_NONE): string {
    if($GLOBALS["is_site_translated"]) {
		if(str_contains($text, "stardewvalleywiki")) {
			return get_translated_wiki_link($text, $GLOBALS["site_language"]);
		}
		
		$text = $GLOBALS["site_translations"][$text] ?? $text;
	}

    return [
        SPACE_NONE => $text,
        SPACE_BEFORE => " $text",
        SPACE_AFTER => "$text ",
        SPACE_BOTH => " $text ",
    ][$option] ?? $text;
}

/**
 * Traduit un lien du wiki si une traduction est disponible.
 *
 * @param string $wiki_link Le lien du wiki à traduire.
 * @param string $lang La langue du site.
 * @return string Le lien du wiki traduit.
 */
function get_translated_wiki_link(string $wiki_link, string $lang): string {
	$wiki_url = parse_url($wiki_link);
	$wiki_page = basename($wiki_url["path"]);

	if(isset($GLOBALS["wiki_link_overload"][$wiki_page])) {
		return $GLOBALS["wiki_link_overload"][$wiki_page];
	}

	$wiki_base_url = [
		"fr" => "https://fr.stardewvalleywiki.com/",
		"de" => "https://de.stardewvalleywiki.com/",
		"es" => "https://es.stardewvalleywiki.com/"
	][$lang];

    if(isset($wiki_url["path"])) {
        return $wiki_base_url . __(str_replace("_", " ", $wiki_page));
    }

	return "";
}

/**
 * Affiche un élément dans une balise <pre> pour le débogage.
 *
 * @param mixed $element L'élément à afficher.
 * @param string $title Le titre de l'élément.
 * @return void
 */
function log_(mixed $element, string $title = null): void {
    if($title !== null) {
		echo "<h2>$title</h2>";
	}
    
	echo "<pre>" . print_r($element, true) . "</pre>";
}

/**
 * Vérifie si la langue actuelle est la langue originale.
 *
 * @param string $language La langue à vérifier.
 * @return bool Indique si la langue est la langue originale.
 */
function is_the_original_language(string $language): bool {
	return $language == "en";
}

/**
 * Vérifie si la langue est une langue supportée.
 *
 * @param string $language La langue à vérifier.
 * @return bool Indique si la langue est supportée.
 */
function is_a_supported_language(string $language): bool {
    return in_array($language, get_supported_languages());
}

/**
 * Récupère les langues supportées.
 *
 * @return array Les langues supportées.
 */
function get_supported_languages(): array {
	return [
		"en", "fr", "de", "es"
	];
}

/**
 * Récupère la langue du site.
 *
 * @return string La langue du site.
 */
function get_site_language(): string {
	return $GLOBALS["site_language"] ?? "en";
}

/**
 * Récupère l'URL correcte à utiliser en fonction de la requête actuelle.
 *
 * @return string L'URL correcte.
 */
function get_correct_url(): string {
	return (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : $_SERVER["REQUEST_URI"];
}

/**
 * Définit la langue du site.
 *
 * @return void
 */
function define_site_language(): void {
	$url = get_correct_url();

	$url_without_query = parse_url($url, PHP_URL_PATH);
	$url_trimmed = rtrim($url_without_query, '/');
	$lang = basename($url_trimmed);
	$GLOBALS["site_language"] = (is_a_supported_language($lang)) ? $lang : "en";
}

/**
 * Charge tous les fichiers JSON contenant les informations de Stardew Valley.
 *
 * @return void
 */
function load_all_json(): void {
	$all_json = [
		"achievements_details",
		"achievements",
		"adventurer's_guild_goals",
		"all_dates",
		"all_items",
		"artifacts",
		"books",
		"bundles",
		"cooking_recipes",
		"crafting_recipes",
		"custom_ids",
		"enemies",
		"farm_animals",
		"festivals",
		"fish",
		"locations_to_visit",
		"marriables",
		"masteries",
		"minerals",
		"perfection_elements",
		"quests",
		"secret_notes",
		"shipped_items",
		"skills",
		"special_orders",
		"unlockables",
		"villagers_birthday",
		"villagers",
		"wiki_links"
	];

	foreach($all_json as $json_file) {
		$GLOBALS["json"][$json_file] = decode($json_file);
	}

}

/**
 * Récupère l'URL racine du site en fonction de l'environnement.
 *
 * @return string La racine du site.
 */
function get_site_root(): string {
	if(is_on_localhost()) {
		return "http://localhost/travail/stardew_dashboard";
	}
	
	$protocol = (empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "off") ? "http" : "https";
	return "$protocol://stardew-dashboard.42web.io";
}

/**
 * Récupère le répertoire racine du site en fonction de l'environnement.
 * 
 * @return string Le répertoire racine du site.
 */
function get_site_directory(): string {
	return strstr(__DIR__, 'stardew_dashboard', true) . 'stardew_dashboard';
}

/**
 * Récupère le répertoire des fichiers JSON.
 * 
 * @return string Le répertoire des fichiers JSON.
 */
function get_json_folder(): string {
    return get_site_root() . "/data/json";
}

/**
 * Récupère le répertoire des langues.
 * 
 * @return string Le répertoire des langues.
 */
function get_languages_folder(): string {
    return get_site_root() . "/locales/languages";
}

/**
 * Récupère le répertoire des sauvegardes.
 * 
 * @param bool $use_directory Indique si le répertoire ou l'URL absolue doit être retourné.
 * @return string Le répertoire des sauvegardes.
 */
function get_saves_folder(bool $use_directory = false): string {
    if($use_directory) {
		return get_site_directory() . "/data/saves";
	}

	return get_site_root() . "/data/saves";
}

/**
 * Vérifie si une sauvegarde existe.
 * 
 * @param string $save Le nom de la sauvegarde.
 * @return bool Indique si la sauvegarde existe.
 */
function does_save_exists(string $save): bool {
    return is_file(get_saves_folder(true) . "/$save");
}

/**
 * Récupère le répertoire des images.
 * 
 * @param bool $is_external Indique si l'URL doit être externe ou local.
 * @return string Le répertoire des images.
 */
function get_images_folder(bool $is_external = false): string {
	return ($is_external || !is_on_localhost()) ? get_github_assets_url() : get_site_root() . "/assets/images";
}

/**
 * Récupère l'URL des assets sur GitHub.
 * 
 * @return string L'URL des assets sur GitHub.
 */
function get_github_assets_url(): string {
	return "https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/assets/images";
}

/**
 * Vérifie si le site est en localhost ou non.
 * 
 * @return bool Indique si le site est en localhost.
 */
function is_on_localhost(): bool {
	return $_SERVER["HTTP_HOST"] === "localhost";
}

/**
 * Vérifie si la sauvegarde est plus ancienne que 1.6.0.
 * 
 * @return bool Indique si la sauvegarde est plus ancienne ou non.
 */
function is_game_older_than_1_6(): bool {
	return ($GLOBALS["game_version_score"] < get_game_version_score("1.6.0"));
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

    if($display_date) {
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
	if($lang === "fr") {
		return number_format($number, 0, ",", " ");
	}

	return number_format($number);
} 

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

    if(substr($string, -1) === "_") {
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
 * Formate un fichier JSON en fonction de la version du jeu.
 * 
 * @param string $json_name Le nom du fichier JSON.
 * @param bool $version_controller Indique si la version du jeu doit être contrôlée.
 * @return array Le fichier JSON formaté.
 */
function sanitize_json_with_version(string $json_name, bool $version_controller = false): array {
	$original_json = $GLOBALS["json"][$json_name];
	$game_version_score = $GLOBALS["game_version_score"] ?? "";
	$sanitize_json = [];

	foreach($original_json as $key => $json_version) {
		if($game_version_score > get_game_version_score($key) || !$version_controller) {
			$sanitize_json += $json_version;
		}
	}
	
	return $sanitize_json;
}

/**
 * Trouve une référence dans un fichier JSON.
 * 
 * @param mixed $id L'identifiant de la référence.
 * @param string $file Le fichier JSON.
 * @return mixed La référence trouvée.
 */
function find_reference_in_json(mixed $id, string $file): mixed {
    $json_file = sanitize_json_with_version($file);
    return isset($json_file[$id]) ? $json_file[$id] : null;
}

/**
 * Récupère l'identifiant correct en fonction du type de données.
 * 
 * @param mixed &$id L'identifiant à vérifier.
 * @return int L'identifiant correct.
 */
function get_correct_id(mixed &$id): int {
	if(!filter_var((int) $id, FILTER_VALIDATE_INT)) {
		return get_custom_id($id);
	}

	return (int) $id;
}

/**
 * Récupère l'identifiant personnalisé en fonction du nom de l'élément.
 * 
 * @param string $item Le nom de l'élément.
 * @return int L'identifiant personnalisé.
 */
function get_custom_id(string $item): int {
    return array_search($item, $GLOBALS["json"]["custom_ids"]);
}

/**
 * Récupère l'identifiant de l'élément en fonction de son nom.
 * 
 * @param string $name Le nom de l'élément.
 * @return int L'identifiant de l'élément.
 */
function get_item_id_by_name(string $name): int {
	return array_search($name, $GLOBALS["json"]["all_items"]) ?? 0;
}

/**
 * Récupère le nom de l'élément en fonction de son identifiant.
 * 
 * @param int $id L'identifiant de l'élément.
 * @return string Le nom de l'élément.
 */
function get_item_name_by_id(int $id): string {
	return $GLOBALS["json"]["all_items"][$id] ?? "None";
}

/**
 * Récupère le lien du wiki en fonction de l'identifiant de l'élément.
 * 
 * @param int $id L'identifiant de l'élément.
 * @return string Le lien du wiki.
 */
function get_wiki_link(int $id): string {
	return htmlspecialchars(
		__($GLOBALS["json"]["wiki_links"][$id], ENT_QUOTES, 'UTF-8')
	);
}

/**
 * Récupère le lien du wiki en fonction du nom de l'élément.
 * 
 * @param string $name Le nom de l'élément.
 * @return string Le lien du wiki.
 */
function get_wiki_link_by_name(string $name): string {
	$wiki_link = "https://stardewvalleywiki.com/" . [
		"achievements" => __("Achievements"),
		"children"     => __("Children"),
		"festival"     => __("Festivals"),
		"mastery_cave" => __("Mastery_Cave"),
		"secret_notes" => __("Secret_Notes"),
		"journal_scraps" => __("Journal_Scraps"),
		"skills"       => __("Skills")
	][$name] ?? "";

	return __($wiki_link);
}

/**
 * Vérifie si toutes les clés spécifiées existent dans un tableau.
 * 
 * @param array $keys Les clés à vérifier.
 * @param array $array Le tableau à vérifier.
 * @return bool Indique si toutes les clés existent.
 */
function array_keys_exists(array $keys, array $array): bool {
    return count(array_diff_key(array_flip($keys), $array)) === 0;
}

/**
 * Vérifie si un objet est vide.
 * 
 * @param object $object L'objet à vérifier.
 * @return bool Indique si l'objet est vide.
 */
function is_object_empty(object $object): bool {
	return ($object->attributes()->count() === 0);
}

/**
 * Décode un fichier JSON depuis une URL ou un chemin local.
 * 
 * @param string $filename Le nom du fichier JSON.
 * @param string $path Le chemin du fichier JSON.
 * @return array Le fichier JSON décodé.
 */
function decode(string $filename, string $path = null): array {
	$path = $path ?? get_json_folder();
	$url = "$path/$filename.json";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response, true);
}

/**
 * Récupère le temps de jeu du joueur.
 * 
 * @return string Le temps de jeu du joueur.
 */
function get_game_duration(): string {
	$player_game_duration = (int) $GLOBALS["untreated_player_data"]->millisecondsPlayed;
    $total_seconds = intdiv($player_game_duration, 1000);
    $seconds      = $total_seconds % 60;
    $total_minutes = intdiv($total_seconds, 60);
    $minutes      = $total_minutes % 60;
    $hours        = intdiv($total_minutes, 60);
	
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

/**
 * Récupère le nombre de joueurs dans le fichier JSON.
 * 
 * @return int Le nombre de joueurs.
 */
function get_number_of_player(): int {
	return count($GLOBALS["all_players_data"]);
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
 * Récupère la taille maximale de téléchargement autorisée en PHP.
 * 
 * @return string La taille maximale de téléchargement autorisée.
 */
function get_php_max_upload_size(): string {
	$post_max_size_bytes = in_bytes_conversion(ini_get("post_max_size"), "server");
	return json_encode([
        "post_max_size" => $post_max_size_bytes
    ]);
}

/**
 * Vérifie si l'utilisateur est sur un appareil mobile.
 * 
 * @return bool Indique si l'utilisateur est sur un appareil mobile.
 */
function is_a_mobile_device(): bool {
	return (
		stristr($_SERVER["HTTP_USER_AGENT"], "Android") ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPod") !== false ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPhone") !== false 
	);
}

/**
 * Récupère les informations des contributeurs du projet.
 * 
 * @return array Les informations des contributeurs.
 */
function get_contributors(): array {
	return [
		[
			"name" => "Romain",
			"icon" => "romain",
			"texts" => [
				__("Romain is a hard-working web developer. He loves taking on challenges and always going the extra mile."),
				__("He took care of the Front-End, and helped Nicolas with the Back-End.")
			],
			"socials" => [
				"github" => [
					"url" => "https://github.com/BreadyBred",
					"on_display" => true
				],
				"linkedin" => [
					"url" => "https://www.linkedin.com/in/romain-gerard/",
					"on_display" => true
				],
				"website" => [
					"url" => "https://romain-gerard.com/",
					"on_display" => true
				],
				"codewars" => [
					"url" => "https://www.codewars.com/users/BreadyBred",
					"on_display" => true
				]
			]
		],
		[
			"name" => "Nicolas",
			"icon" => "nicolas",
			"texts" => [
				__("Nicolas is passionate about sleep and web development. He works as a web developer at Neoma Business School."),
				__("He took care of the Back-End of the website, as well as the UX / UI design.")
			],
			"socials" => [
				"github" => [
					"url" => "https://github.com/NicolasVero",
					"on_display" => true
				],
				"linkedin" => [
					"url" => "https://www.linkedin.com/in/nicolas-vero/",
					"on_display" => true
				],
				"website" => [
					"url" => "https://nicolas-vero.fr/",
					"on_display" => false
				],
				"codewars" => [
					"url" => "https://www.codewars.com/users/NicolasVero",
					"on_display" => true
				]
			]
		]
	];
}

/**
 * Récupère les noms des joueurs.
 * 
 * @return array Les noms des joueurs.
 */
function get_players_name(): array {
	$players_data = $GLOBALS["all_players_data"];
	$players_names = [];

	for($i = 0; $i < count($players_data); $i++) {
		array_push($players_names, $players_data[$i]["general"]["name"]);
	}

	return $players_names;
}

/**
 * Récupère le script pour charger les éléments du tableau de bord.
 * 
 * @return string Le script pour charger les éléments du tableau de bord.
 */
function get_script_loader(): string {
	return "
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				load_dashboard_elements();
			});
		</script>
	";
}

/**
 * Renvoie la chaîne de caractères pour les galleries vides.
 * 
 * @return string La chaîne de caractères.
 */
function no_items_placeholder(): string {
	return __("Nothing to see here");
}

/**
 * Génère un texte d'infobulle basé sur les données du joueur et le type de donnée fourni.
 * 
 * @param array $player_data Les données du joueur.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @param string $data_type Le type de donnée.
 * @return string Le texte de l'infobulle.
 */
function get_tooltip_text(array $player_data, string $json_line_name, string $data_type): string {
	if(!array_key_exists($json_line_name, $player_data) || !isset($player_data[$json_line_name])) {
		return __($json_line_name);
	}
	$data_array = $player_data[$json_line_name];

    extract($data_array); //? ?$counter, ?$caught_counter, ?$killed_counter, ?$max_length, ?$description

    switch($data_type) {
		case "shipped_items" :
			$tooltip_end_text = $counter . __("shipped", SPACE_BEFORE);
			break;
			
        case "farm_animals" : 
			$tooltip_end_text = $counter . __("in your farm", SPACE_BEFORE);
			break;

        case "fish" : 
			$tooltip_end_text = __("caught", SPACE_AFTER) . $caught_counter . __("times", SPACE_BEFORE) . (($max_length > 0) ? " ($max_length " . __("inches") . ")" : "");
			break;

        case "enemies" : 
			$tooltip_end_text = $killed_counter . __("killed", SPACE_BEFORE);
			break;

        case "cooking_recipes" :
			$tooltip_end_text = ($counter === 0) ? __("not cooked yet") : __("cooked", SPACE_AFTER) . (int) $counter . __("times", SPACE_BEFORE);
			break;

		case "crafting_recipes" :
			$tooltip_end_text = ($counter === 0) ? __("not crafted yet") : __("crafted", SPACE_AFTER) . (int) $counter . __("times", SPACE_BEFORE);
			break;

        case "achievements" :
			$tooltip_end_text = __($description);
			break;

        case "artifacts":
        case "minerals":  
			$tooltip_end_text = ($counter === 0) ? __("not given yet") : __("given to museum");
			break;

		case "locations_to_visit" :
        default : 
			return __($json_line_name);
	}

	return __($json_line_name) . ": $tooltip_end_text";
}

/**
 * Indique si un objectif est complété.
 * 
 * @param int $current_counter Le compteur actuel.
 * @param int $limit La limite de l'objectif.
 * @return bool Indique si l'objectif est complété.
 */
function is_objective_completed(int $current_counter, int $limit): bool {
    return ($current_counter >= $limit);
}

/**
 * Récupère le pourcentage de complétion d'un objectif.
 * 
 * @param int $max_amount La quantité maximale de l'objectif.
 * @param int $current_amount La quantité actuelle de l'objectif.
 * @return float Le pourcentage de complétion de l'objectif.
 */
function get_element_completion_percentage(int $max_amount, int $current_amount): float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}

/**
 * Indique si un élément est présent dans le courrier d'un joueur.
 * 
 * @param string $element L'élément à vérifier.
 * @return int Indique si l'élément est présent.
 */
function has_element_in_mail(string $element): int {
	$player_data = $GLOBALS["untreated_player_data"] ?? $GLOBALS["untreated_all_players_data"]->player;
    return (in_array($element, (array) $player_data->mailReceived->string)) ? 1 : 0;
}

/**
 * Vérifie si un joueur possède un élèment.
 * 
 * @param object $element L'élément à vérifier.
 * @return int Indique si l'élément est possédé.
 */
function has_element(object $element): int {
    return !empty((array) $element);
}

/**
 * Vérifie si un joueur possède un élèment en fonction de la version du jeu.
 * 
 * @param string $element_older_version L'élément pour les versions antérieures à 1.6.0.
 * @param string $element_newer_version L'élément pour les versions postérieures à 1.6.0.
 * @return int Indique si l'élément est possédé.
 */
function has_element_based_on_version(string $element_older_version, string $element_newer_version): int {
	$player_data = $GLOBALS["untreated_player_data"];

	if(is_game_older_than_1_6()) {
		return has_element($player_data->$element_older_version);
	}

	return has_element_in_mail($element_newer_version);
}

/**
 * Récupère le score de la version du jeu.
 * 
 * @param string $version La version du jeu.
 * @return int Le score de la version du jeu.
 */
function get_game_version_score(string $version): int {
	$version_numbers = explode(".", $version);

	while(count($version_numbers) < 3) {
        $version_numbers[] = 0;
    }

	$version_numbers = array_reverse($version_numbers);
	$score = 0;

	for($i = 0; $i < count($version_numbers); $i++) {
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
 * Récupère les données des éléments possédés par un joueur.
 * 
 * @param object $data Les données du joueur.
 * @param string $filename Le nom du fichier JSON.
 * @return array Les données des éléments possédés par un joueur.
 */
function get_player_items_list(object $data, string $filename): array {
	if(is_game_older_than_1_6()) {
		return [];
	}

	$items_data = [];

	foreach($data->item as $item) {
		$item_id = format_original_data_string($item->key->string);
		$item_id = get_correct_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if(empty($item_reference)) {
			continue;
		}

		$items_data[$item_reference] = [ "id" => $item_id ];
	}
	
	return $items_data;
}

/**
 * Récupère la classe d'un élèment en fonction de la version du jeu.
 * 
 * @param string $version La version du jeu.
 * @return string La classe de l'élément.
 */
function get_version_class(string $version): string {
	return ($GLOBALS["game_version_score"] < get_game_version_score($version)) ? "newer-version" : "older-version";
}

/**
 * Récupère les classes d'un élément trouvé.
 * 
 * @param array $player_data Les données du joueur.
 * @param string $json_filename Le nom du fichier JSON.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @param bool $is_found Indique si l'élément est trouvé.
 * @return string Les classes de l'élément trouvé.
 */
function get_found_classes(array $player_data, string $json_filename, string $json_line_name, bool $is_found): string {
	$classes = ($is_found) ? "found" : "not-found";
	
	if(in_array($json_filename, ["cooking_recipes", "crafting_recipes", "artifacts", "minerals"])) {
		if($is_found && $player_data[$json_line_name]["counter"] === 0) {
			$classes .= " unused";
		}
	}
	return $classes;
}

/**
 * Génère le texte concernant les contributeurs d'un projet.
 * 
 * @param array $options Les options pour générer le texte.
 * @return string Le texte concernant les contributeurs d'un projet.
 */
function display_project_contributor(array $options): string {
    extract($options); //? $name, $icon, $texts, $socials

    $images_path = get_images_folder();
    $portrait =  "$images_path/content/$icon.png";
    $presentation = "";
    $socials_links = "";

    foreach($texts as $text) {
        $presentation .= "<span>$text</span>";
    }

    foreach($socials as $social_name => $social) {
        extract($social); //? $url, $on_display
        if($on_display) {
            $socials_links .= "<a href='$url' rel='noreferrer' target='_blank'><img src='$images_path/social/$social_name.png' alt='$social_name'/></a>";
        }
    }

    return "
        <span>
            <img src='$portrait' class='character-image $icon' alt='$name'/>
            <span>
                <span class='character-presentation'>
                    $presentation
                </span>
                <span class='socials'>
                    $socials_links
                </span>
            </span>
        </span>
    ";
}

/**
 * Génère le texte d'affichage de la bande de chargement.
 * 
 * @return string Le texte d'affichage de la bande de chargement.
 */
function display_loading_strip(): string {
	$images_path = get_images_folder();
	$loading_translation = __("loading");
	return "<img src='$images_path/content/strip_$loading_translation.gif' id='loading-strip' class='loading' alt=''/>";
}

/**
 * Recherche des balises XML correspondant à un chemin spécifié dans un objet XML.
 * 
 * @param object $xml_object L'objet XML.
 * @param string $tag_path Le chemin de la balise XML à rechercher.
 * @return array Les balises XML trouvées.
 */
function find_xml_tags(object $xml_object, string $tag_path): array {
    $path_elements = explode('.', $tag_path);
    return recursive_xml_search($xml_object, $path_elements);
}

/**
 * Recherche récursive de balises XML en suivant un chemin donné.
 * 
 * @param object $current_level Le niveau actuel de l'objet XML.
 * @param array $remaining_path Le chemin restant à suivre.
 * @return array Les balises XML trouvées.
 */
function recursive_xml_search(object $current_level, array $remaining_path): array {
    $results = [];

    if(empty($remaining_path)) {
        return is_array($current_level) ? $current_level : [$current_level];
    }

    $current_tag = $remaining_path[0];

    if(!isset($current_level->$current_tag)) {
        return $results;
    }

    if(count($remaining_path) == 1) {
        foreach($current_level->$current_tag as $item) {
            $results[] = $item;
        }
		
        return $results;
    }

    $child_remaining_path = array_slice($remaining_path, 1);

    foreach($current_level->$current_tag as $child) {
        $child_results = recursive_xml_search($child, $child_remaining_path);
        $results = array_merge($results, $child_results);
    }

    return $results;
}

/**
 * Récupère l'index d'une balise XML correspondant à un chemin spécifié dans un objet XML.
 * 
 * @param object $general_data Les données générales du joueur.
 * @param string $searched_location La balise XML à rechercher.
 * @return int L'index de la balise XML trouvée.
 */
function get_gamelocation_index(object $general_data, string $searched_location): int {
	$index = 0;
	$locations = $general_data->locations->GameLocation;

	foreach($locations as $location) {
		if(isset($location->$searched_location)) {
			break;
		}
		
		$index++;
	}

	return $index;
}

/**
 * Récupère l'index de la balise XML correspondant au musée.
 * 
 * @return int L'index de la balise XML GameLocation du musée.
 */
function get_museum_index(): int {
    $untreated_all_data = $GLOBALS["untreated_all_players_data"];
	return get_gamelocation_index($untreated_all_data, "museumPieces");
}