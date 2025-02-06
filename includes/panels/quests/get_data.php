<?php

function get_story_quest_data(array $quest): array {
	return [
		"time_limited"	=> false,
		"objective"   	=> $quest["objective"],
		"description" 	=> $quest["description"],
		"title"       	=> $quest["title"],
		"rewards"     	=> $quest["reward"]
	];
}

function find_reference(string $reference_id): string {
	$reference_id = format_original_data_string($reference_id);
	$json_list = [
		"shipped_items",
		"fish",
		"minerals"
	];

	foreach($json_list as $json) {
		if(isset($reference) && $reference !== null) {
			continue;
		}

		$reference = find_reference_in_json($reference_id, $json);
	}

	return $reference;
}

function get_daily_quest_data(object $quest): array|null {
	$quest_type = (int) $quest->questType;
	$days_left = (int) $quest->daysLeft;
	$rewards = [(int) $quest->reward];
	$target = $quest->target;
	$quest_configs = get_daily_quest_configs();

	if(!isset($quest_configs[$quest_type])) {
		return null;
	}
	
	$config = $quest_configs[$quest_type];

	$goal_name = $config["goal_name"]($quest);
	$keyword = $config["keyword"];
	$keyword_ing = $config["keyword_ing"];
	$number_to_get = $config["number_to_get"]($quest);
	$number_obtained = $config["number_obtained"];

	$title = __("$keyword_ing Quest");
	$description = __("Help") . " $target " . __("with the") . __($keyword_ing, SPACE_BOTH) . __("request") . ".";
	$objective = __($keyword) . " $number_to_get " . __($goal_name) . __("for", SPACE_BEFORE) . " $target: $number_obtained/$number_to_get";

	return [
		"time_limited"	=> true,
		"objective"   	=> $objective,
		"description" 	=> $description,
		"title"       	=> $title,
		"daysLeft"    	=> $days_left,
		"rewards"     	=> $rewards
	];
}

function get_special_order_data(object $special_order): array|null {
	$special_orders_json = sanitize_json_with_version("special_orders", true);

	if(((string) $special_order->questState) !== "InProgress") {
		return null;
	}

	$is_qi_order = ((string) $special_order->orderType === "Qi");
	$title = ($is_qi_order) ? "QI's Special Order" : "Weekly Special Order";
	$description = $special_orders_json[(string) $special_order->questKey];
	$days_left = (int) $special_order->dueDate - get_number_of_days_ingame();
	
	$target = (string) $special_order->requester;
	$number_to_get = (int) $special_order->objectives->maxCount;
	$number_obtained = (int) $special_order->objectives->currentCount;
	$objective = "$target, $description: $number_obtained/$number_to_get";

	$rewards = [];
	foreach($special_order->rewards as $reward) {
		if(!isset($reward->amount)) {
			continue;
		}

		if($is_qi_order) {
			$rewards[] = (int) $reward->amount->int . "_q";
		} else {
			$rewards[] = ((int) $reward->amount->int) * ((int) $reward->multiplier->float);
		}
	}
		
	return [
		"time_limited"	=> true,
		"objective"   	=> $objective,
		"description" 	=> $description,
		"title"       	=> $title,
		"daysLeft"    	=> $days_left,
		"rewards"     	=> $rewards
	];
}

function get_player_quest_log(): array {
	$entire_data = $GLOBALS["untreated_all_players_data"];
	$player_quest_log = $GLOBALS["untreated_player_data"]->questLog;
	$quests_data = [];

	foreach($player_quest_log->Quest as $quest) {
		$quest_id = (int) $quest->id;
		$quest_reference = find_reference_in_json(
			$quest_id,
			"quests"
		);

		// if -> Quête histoire // else -> Quête daily
		if(!empty($quest_reference)){
			$quests_data[] = get_story_quest_data($quest_reference);
		} else {
			if(($quest_data = get_daily_quest_data($quest)) !== null) {
				$quests_data[] = $quest_data;
			}
		}
	}

	// Special Orders (Weekly)
	foreach($entire_data->specialOrders->SpecialOrder as $special_order) {
		if(($special_order_data = get_special_order_data($special_order)) !== null) {
			$quests_data[] = $special_order_data;
		}
	}

	return $quests_data;
}

function get_daily_quest_configs() {
	return [
		3 => [
			"goal_name" => fn($quest) => find_reference($quest->item),
			"keyword" => "Deliver",
			"keyword_ing" => "Delivering",
			"number_to_get" => fn($quest) => $quest->number,
			"number_obtained" => 0,
		],
		4 => [
			"goal_name" => fn($quest) => $quest->monsterName,
			"keyword" => "Kill",
			"keyword_ing" => "Killing",
			"number_to_get" => fn($quest) => $quest->numberToKill,
			"number_obtained" => fn($quest) => $quest->numberKilled,
		],
		5 => [
			"goal_name" => "people",
			"keyword" => "Talk to",
			"keyword_ing" => "Socializing",
			"number_to_get" => fn($quest) => $quest->total,
			"number_obtained" => fn($quest) => $quest->whoToGreet,
		],
		7 => [
			"goal_name" => fn($quest) => find_reference_in_json(format_original_data_string($quest->whichFish), "fish"),
			"keyword" => "Fish",
			"keyword_ing" => "Fishing",
			"number_to_get" => fn($quest) => $quest->numberToFish,
			"number_obtained" => 0,
		],
		10 => [
			"goal_name" => fn($quest) => find_reference_in_json(format_original_data_string($quest->resource), "fish"),
			"keyword" => "Fish",
			"keyword_ing" => "Fishing",
			"number_to_get" => fn($quest) => $quest->number,
			"number_obtained" => 0,
		],
	];
}