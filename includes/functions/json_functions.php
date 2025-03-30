<?php

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
 * Décode un fichier JSON depuis une URL ou un chemin local.
 * 
 * @param string $filename Le nom du fichier JSON.
 * @param string $path Le chemin du fichier JSON.
 * @return array Le fichier JSON décodé.
 */
function decode(string $filename, ?string $path = null): array {
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
 * Formate un fichier JSON en fonction de la version du jeu.
 * 
 * @param string $json_name Le nom du fichier JSON.
 * @param bool $version_controller Indique si la version du jeu doit être contrôlée.
 * @return array Le fichier JSON formaté.
 */
function sanitize_json_with_version(string $json_name, bool $version_controller = false): array {
	$original_json = $GLOBALS["json"][$json_name];
	$game_version_score = get_game_version_score($GLOBALS["game_version"]);
	$sanitize_json = [];

	foreach($original_json as $key => $json_version) {
		if ($game_version_score > get_game_version_score($key) || !$version_controller) {
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
	if (!filter_var((int) $id, FILTER_VALIDATE_INT)) {
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
