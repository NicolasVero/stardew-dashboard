<?php

/**
 * Récupère les données de l'amitié du joueur.
 * 
 * @return array Les données de l'amitié du joueur.
 */
function get_player_friendship_data(): array {
	$player_friendships = $GLOBALS["untreated_player_data"]->friendshipData;
	$villagers_json = sanitize_json_with_version("villagers");
	$birthday_json = sanitize_json_with_version("villagers_birthday");
	$friends_data = [];

	foreach($player_friendships->item as $friend) {
		$friend_name = (string) $friend->key->string;

		if (!in_array($friend_name, $villagers_json)) {
			continue;
		}

		$friends_data[$friend_name] = [
			"id"              => get_custom_id($friend_name),
			"points"          => (int) $friend->value->Friendship->Points,
			"friend_level"    => (int) floor(($friend->value->Friendship->Points) / 250),
			"birthday"        => $birthday_json[get_custom_id($friend_name)],
			"status"          => (string) $friend->value->Friendship->Status,
			"week_gifts"      => (int) $friend->value->Friendship->GiftsThisWeek
		];
	}

	uasort($friends_data, function (array $a, array $b): bool {
		return $b["points"] - $a["points"];
	});

	return $friends_data; 
}

/**
 * Prépare et formate les données de l'amitié du joueur.
 * 
 * @param array $friendship_info Les données de l'amitié du joueur.
 * @return array Les données de l'amitié du joueur formatées.
 */
function prepare_all_friendship_info(array $friendship_info): array {
    $marriables_npc = sanitize_json_with_version("marriables");

    $friendship_info = get_verified_friend_data($friendship_info);
    extract($friendship_info); //? $id, $points, $friend_level, $birthday, $status, $week_gifts

    $villager_name = get_item_name_by_id($id);
    $wiki_link = get_wiki_link(get_item_id_by_name($villager_name));

    $can_be_married = in_array($villager_name, $marriables_npc) && $status === "Friendly";
    $hearts_structure = get_hearts_structure([
        "status" => $status,
        "friend_level" => $friend_level,
        "can_be_married" => $can_be_married
    ]);

    $is_birthday = $birthday && is_this_the_same_day($birthday);
    $birthdate = $birthday ? __("Day", SPACE_AFTER) . explode("/", $birthday)[0] . __("of " . explode("/", $birthday)[1], SPACE_BEFORE) : "Unknown";

	return [
		"villager_name" => $villager_name,
		"status" => $status,
		"points" => $points,
		"hearts_structure" => $hearts_structure,
		"week_gifts" => $week_gifts,
		"wiki_link" => $wiki_link,
		"birthday" => [
			"is_birthday" => $is_birthday,
			"birthdate" => $birthdate
		]
	];
}

/**
 * Vérifie et complète les données d'amitié du joueur.
 *
 * @param array $friendship_info Les données brutes de l'amitié.
 * @return array Les données vérifiées et complétées.
 */
function get_verified_friend_data(array $friendship_info): array {
    $birthday_json = sanitize_json_with_version("villagers_birthday");

    extract($friendship_info); //? $id, $points, $friend_level, $birthday, $status, $week_gifts || $id

    $points = $points ?? 0;
    $friend_level = $friend_level ?? 0;
    $birthday = $birthday_json[$id] ?? "1/spring";
    $status = $status ?? "Unknown";

    $week_gifts = (isset($week_gifts)) ? [
        $week_gifts > 0 ? "gifted" : "not-gifted",
        $week_gifts === 2 ? "gifted" : "not-gifted"
    ] : ["not-gifted", "not-gifted"];

    return [
        'id' => $id,
        'points' => $points,
        'friend_level' => $friend_level,
        'birthday' => $birthday,
        'status' => $status,
        'week_gifts' => $week_gifts
    ];
}

/**
 * Génère le code HTML des cœurs d'amitié d'un villageois.
 *
 * @param array $hearts_info Informations sur les cœurs (statut, niveau d'amitié, mariage possible).
 * @return string Le code HTML des cœurs d'amitié.
 */
function get_hearts_structure(array $hearts_info): string {
    $images_path = get_images_folder();

	extract($hearts_info); //? $status, $friend_level, $can_be_married

    $max_heart = ($status) === "Married" ? 14 : 10;

	$hearts_structure = "";
    for ($i = 1; $i <= $max_heart; $i++) {
        $heart_icon = "$images_path/icons/" . (($i > 8 && $can_be_married) ? "locked_heart.png" : (($friend_level >= $i) ? "heart.png" : "empty_heart.png"));
        $hearts_structure .= "<img src='$heart_icon' class='hearts' alt=''>";
    }

	return $hearts_structure;
}
