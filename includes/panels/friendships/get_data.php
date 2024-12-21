<?php 

function get_player_friendship_data(): array {
	$player_friendships = $GLOBALS["untreated_player_data"]->friendshipData;
	$villagers_json = sanitize_json_with_version("villagers");
	$birthday_json = sanitize_json_with_version("villagers_birthday");
	$friends_data = [];

	foreach($player_friendships->item as $friend) {
		$friend_name = (string) $friend->key->string;

		if(!in_array($friend_name, $villagers_json)) {
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

	uasort($friends_data, function ($a, $b) {
		return $b["points"] - $a["points"];
	});

	return $friends_data; 
}