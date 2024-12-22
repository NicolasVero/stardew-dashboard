<?php

function log_(mixed $element, string $title = null): void {
    if($title !== null) {
		echo "<h2>$title</h2>";
	}
    
	echo "<pre>" . print_r($element, true) . "</pre>";
}

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

function get_site_root(): string {
	if(is_on_localhost()) {
		return "http://localhost/travail/stardew_dashboard";
	}
	
	$protocol = (empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "off") ? "http" : "https";
	return "$protocol://stardew-dashboard.42web.io";
}

function get_site_directory(): string {
	return strstr(__DIR__, 'stardew_dashboard', true) . 'stardew_dashboard';
}

function get_json_folder(): string {
    return get_site_root() . "/data/json";
}

function get_saves_folder(bool $use_directory = false): string {
    if($use_directory) {
		return get_site_directory() . "/data/saves";
	}

	return get_site_root() . "/data/saves";
}

function does_save_exists(string $save): bool {
    return is_file(get_saves_folder(true) . "/$save");
}

function get_images_folder(bool $is_external = false): string {
	return ($is_external || !is_on_localhost()) ? get_github_assets_url() : get_site_root() . "/assets/images";
}

function get_github_assets_url(): string {
	return "https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/assets/images";
}

function is_on_localhost(): bool {
	return $_SERVER["HTTP_HOST"] === "localhost";
}

function is_game_older_than_1_6(): bool {
	return ($GLOBALS["game_version_score"] < get_game_version_score("1.6.0"));
}

function get_formatted_date(bool $display_date = true): mixed {
	$data = $GLOBALS["untreated_player_data"];
    $day    = $data->dayOfMonthForSaveGame;
    $season = ["spring", "summer", "fall", "winter"][$data->seasonForSaveGame % 4];
    $year   = $data->yearForSaveGame;

    if($display_date) {
		return "Day $day of $season, Year $year";
	}

    return [
        "day" => $day,
        "season" => $season,
        "year" => $year
	];
}

function formate_number(int $number, string $lang = "en"): string {
	if($lang === "fr") {
		return number_format($number, 0, ",", " ");
	}

	return number_format($number);
} 

function formate_text_for_file(string $string): string {
    $search  = [" ", "'", "(", ")", ",", ".", ":"];
    $replace = ["_", ""  , "" , "", "", "", ""   ];
    $string = str_replace($search, $replace, $string);
    $string = strtolower($string);

    if(substr($string, -1) === "_") {
        $string = substr($string, 0, -1);
    }

    return $string;
}

function formate_original_data_string(string $data): string {
    return str_replace("(O)", "", $data);
}

function formate_usernames(string $username): string {
	$regex = [
		"à" => "a", "á" => "a", "â" => "a", "ã" => "a", "ä" => "a", "å" => "a", "æ" => "ae",
		"ç" => "c",
		"è" => "e", "é" => "e", "ê" => "e", "ë" => "e",
		"ì" => "i", "í" => "i", "î" => "i", "ï" => "i",
		"ñ" => "n",
		"ò" => "o", "ó" => "o", "ô" => "o", "õ" => "o", "ö" => "o", "ø" => "o",
		"ù" => "u", "ú" => "u", "û" => "u", "ü" => "u",
		"ý" => "y", "ÿ" => "y",
		"À" => "A", "Á" => "A", "Â" => "A", "Ã" => "A", "Ä" => "A", "Å" => "A", "Æ" => "AE",
		"Ç" => "C",
		"È" => "E", "É" => "E", "Ê" => "E", "Ë" => "E",
		"Ì" => "I", "Í" => "I", "Î" => "I", "Ï" => "I",
		"Ñ" => "N",
		"Ò" => "O", "Ó" => "O", "Ô" => "O", "Õ" => "O", "Ö" => "O", "Ø" => "O",
		"Ù" => "U", "Ú" => "U", "Û" => "U", "Ü" => "U",
		"Ý" => "Y"
	];

	return strtr($username, $regex);
}

function in_bytes_conversion(string $size, string $use = "local"): int {
    $unit_to_power = ($use === "local") 
		? ["o"  => 0, "Ko" => 1, "Mo" => 2, "Go" => 3]
		: ["K" => 1, "M" => 2, "G" => 3];

    preg_match("/(\d+)([a-zA-Z]+)/", $size, $matches);
    
    $value = (int) $matches[1];
    $unite = $matches[2];
    
    return $value * pow(1024, $unit_to_power[$unite]);
}

function sanitize_json_with_version(string $json_name, bool $version_controler = false): array {
	$original_json = $GLOBALS["json"][$json_name];
	$game_version_score = $GLOBALS["game_version_score"] ?? "";
	$sanitize_json = [];

	foreach($original_json as $key => $json_version) {
		if($game_version_score > get_game_version_score($key) || !$version_controler) {
			$sanitize_json += $json_version;
		}
	}
	
	return $sanitize_json;
}

function find_reference_in_json(mixed $id, string $file): mixed {
    $json_file = sanitize_json_with_version($file);
    return isset($json_file[$id]) ? $json_file[$id] : null;
}

function get_correct_id(mixed &$id): int {
	if(!filter_var((int) $id, FILTER_VALIDATE_INT)) {
		return get_custom_id($id);
	}

	return (int) $id;
}

function get_custom_id(string $item): int {
    return array_search($item, $GLOBALS["json"]["custom_ids"]);
}

function get_item_id_by_name(string $name): int {
	return array_search($name, $GLOBALS["json"]["all_items"]) ?? 0;
}

function get_item_name_by_id(int $id): string {
	return $GLOBALS["json"]["all_items"][$id] ?? "None";
}

function get_wiki_link(int $id): string {
	return $GLOBALS["json"]["wiki_links"][$id];
}

function get_wiki_link_by_name(string $name): string {
	return "https://stardewvalleywiki.com/" . [
		"achievements" => "Achievements",
		"children"     => "Children",
		"festival"     => "Festivals",
		"mastery_cave" => "Mastery_Cave",
		"secret_notes" => "Secret_Notes",
		"skills"       => "Skills"
	][$name] ?? "";
}

function array_keys_exists(array $keys, array $array): bool {
    return count(array_diff_key(array_flip($keys), $array)) === 0;
}

function is_object_empty(object $object): bool {
	return ($object->attributes()->count() === 0);
}

function decode(string $filename): array {
    $url = get_json_folder() . "/$filename.json";
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response, true);
}

function get_game_duration(): string {
	$player_game_duration = (int) $GLOBALS["untreated_player_data"]->millisecondsPlayed;
    $total_seconds = intdiv($player_game_duration, 1000);
    $seconds      = $total_seconds % 60;
    $total_minutes = intdiv($total_seconds, 60);
    $minutes      = $total_minutes % 60;
    $hours        = intdiv($total_minutes, 60);
	
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

function get_number_of_player(): int {
	return count($GLOBALS["all_players_data"]);
}

function get_number_of_days_ingame(): int {
	$data = $GLOBALS["untreated_player_data"];
    return ((($data->dayOfMonthForSaveGame - 1)) + ($data->seasonForSaveGame * 28) + (($data->yearForSaveGame - 1) * 112));
}

function get_php_max_upload_size(): string {
	$post_max_size_bytes = in_bytes_conversion(ini_get("post_max_size"), "server");
	return json_encode([
        "post_max_size" => $post_max_size_bytes
    ]);
}

function is_a_mobile_device(): bool {
	return (
		stristr($_SERVER["HTTP_USER_AGENT"], "Android") ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPod") !== false ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPhone") !== false 
	);
}

function get_contributors(): array {
	return [
		[
			"name" => "Romain",
			"icon" => "romain",
			"texts" => [
				"Romain is a hard-working web developer. He loves taking on challenges and always going the extra mile.",
				"He took care of the Front-End, and helped Nicolas with the Back-End."
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
				"Nicolas is passionate about sleep and web development. He works as a web developer at Neoma Business School.",
				"He took care of the Back-End of the website, as well as the UX / UI design."
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

function get_players_name(): array {
	$players_data = $GLOBALS["all_players_data"];
	$players_names = [];

	for($i = 0; $i < count($players_data); $i++) {
		array_push($players_names, $players_data[$i]["general"]["name"]);
	}

	return $players_names;
}

function get_script_loader(): string {
	return "
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const players_count = " . count($GLOBALS["players_names"]) . "
				initialize_player_swapper(players_count);
				initialize_settings();
				load_elements();
			});
		</script>
	";
}

function no_items_placeholder(): string {
	return "Nothing to see here";
}

function get_tooltip_text(array $player_data, string $json_line_name, string $data_type): string {
    $data_array = $player_data[$json_line_name];

    if(empty($data_array)) {
        return $json_line_name;
    }

    extract($data_array);

    switch($data_type) {
        case "locations_to_visit" :
            return "$json_line_name";

        case "farm_animals" : 
            return "$json_line_name: $counter in your farm";

        case "fish" : 
            if($max_length > 0) return "$json_line_name: caught $caught_counter times ($max_length inches)";
            return "$json_line_name: caught $caught_counter times";

        case "enemies" : 
            return "$json_line_name: $killed_counter killed";

        case "cooking_recipes" :
            if(!$counter) return "$json_line_name: not cooked yet";
            return "$json_line_name: cooked " . (int) $counter . " times";

		case "crafting_recipes" :
			if(!$counter) return "$json_line_name: not crafted yet";
			return "$json_line_name: crafted " . (int) $counter . " times";

        case "achievements" :
            return "$json_line_name: $description";

        case "artifacts":
        case "minerals":  
            if($counter === 0) return "$json_line_name: not given yet";
            return "$json_line_name: given to museum";

        default : return $json_line_name;
    }
}

function is_objective_completed(int $current_counter, int $limit): bool {
    return ($current_counter >= $limit);
}

function get_element_completion_percentage(int $max_amount, int $current_amount): float {
	return round(($current_amount / $max_amount), 3, PHP_ROUND_HALF_DOWN);
}

function has_element_in_mail(string $element): int {
	$player_data = $GLOBALS["untreated_player_data"] ?? $GLOBALS["untreated_all_players_data"]->player;
    return (in_array($element, (array) $player_data->mailReceived->string)) ? 1 : 0;
}

function has_element(object $element): int {
    return !empty((array) $element);
}

function has_element_based_on_version(string $element_older_version, string $element_newer_version): int {
	$player_data = $GLOBALS["untreated_player_data"];

	if(is_game_older_than_1_6()) {
		return has_element($player_data->$element_older_version);
	}

	return has_element_in_mail($element_newer_version);
}

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

function is_this_the_same_day(string $date): bool {
    extract(get_formatted_date(false)); //? $day, $season, $year
    return $date === "$day/$season";
}

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

function get_player_items_list(object $data, string $filename): array {
	if(is_game_older_than_1_6()) {
		return [];
	}

	$items_data = [];

	foreach($data->item as $item) {
		$item_id = formate_original_data_string($item->key->string);
		$item_id = get_correct_id($item_id);

		$item_reference = find_reference_in_json($item_id, $filename);

		if(empty($item_reference)) {
			continue;
		}

		$items_data[$item_reference] = [ "id" => $item_id ];
	}
	
	return $items_data;
}

function get_version_class(string $version): string {
	return ($GLOBALS["game_version_score"] < get_game_version_score($version)) ? "newer-version" : "older-version";
}

function get_found_classes(array $player_data, string $json_filename, string $json_line_name, bool $is_found): string {
	$classes = ($is_found) ? "found" : "not-found";
	
	if(in_array($json_filename, ["cooking_recipes", "crafting_recipes", "artifacts", "minerals"])) {
		if($is_found && $player_data[$json_line_name]["counter"] === 0) {
			$classes .= " unused";
		}
	}
	return $classes;
}

function get_detailled_gallery_image(string $json_filename, string $json_line_name): string {
	
	$images_path = get_images_folder();

	if(!in_array($json_filename, ["secret_notes"])) {
		return "$images_path/$json_filename/" . formate_text_for_file($json_line_name). ".png";
	}

	$line_name = explode(" ", $json_line_name);
	$icon_name = formate_text_for_file(implode(" ", array_slice($line_name, 0, 2)));
	return "$images_path/icons/$icon_name.png";
}

function get_detailled_gallery_wiki_link(string $json_filename, string $json_line_name): string {
	if(in_array($json_filename, ["achievements", "secret_notes"])) {
		$wiki_url = [
			"achievements" => get_wiki_link_by_name("achievements"),
			"secret_notes" => get_wiki_link_by_name("secret_notes")
		][$json_filename];
	} else {
		$wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
	}

	return $wiki_url;
}