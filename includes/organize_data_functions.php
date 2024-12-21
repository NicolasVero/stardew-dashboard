<?php

function load_save(string $save_file, bool $use_ajax = true): mixed {
    $uploaded_file = $save_file;
    $data = simplexml_load_file($uploaded_file);
    load_all_json();

    $GLOBALS["untreated_all_players_data"] = $data;
    $GLOBALS["game_version"] = $data->gameVersion;
	$GLOBALS["game_version_score"] = (int) get_game_version_score((string) $data->gameVersion);;
	$GLOBALS["should_spawn_monsters"] = (string) $data->shouldSpawnMonsters;
    $GLOBALS["shared_players_data"] = get_shared_aggregated_data();

    $players_data = get_all_players_data();
    $GLOBALS["players_names"] = get_players_name();
    $pages["topbar"] = display_topbar();

    
    for($player_count = 0; $player_count < count($players_data); $player_count++) {
        $GLOBALS["player_id"] = $player_count;
        $pages["player_" . $player_count] = "
            <div class='player_container player_{$player_count}_container'>" . 
                display_page() . "
            </div>
        ";
    }

    if($use_ajax) {
        return [
            "players" => $GLOBALS["players_names"],
            "html" => $pages,
            "code" => "success"
        ];
    } else {
        $structure = display_landing_page(false);

        foreach($pages as $page) {
            $structure .= $page;
        }
        
        $structure .= get_script_loader();
        echo $structure;
    }
    
    return true;
}

function get_farmhands(): array {
    $data = $GLOBALS["untreated_all_players_data"];
    $all_farmhands = [];

    if(is_game_older_than_1_6()) {
        foreach($data->locations->GameLocation as $game_location) {
            if(!isset($game_location->buildings)) {
                continue;
            }

            foreach($game_location->buildings->Building as $building) {
                if(!isset($building->indoors->farmhand) || (string) $building->indoors->farmhand->name === "") {
                    continue;
                }

                $farmhand = $building->indoors->farmhand;
                array_push($all_farmhands, $farmhand);
            }
        }
    } else {
        if(empty($data->farmhands)) {
            return [];
        }

        foreach($data->farmhands->Farmer as $farmhand) {
            if((string) $farmhand->name === "") {
                continue;
            }

            array_push($all_farmhands, $farmhand);
        }
    }

    return $all_farmhands;
}

function get_all_players_data(): array {
    $players_data = [];
    $data = $GLOBALS["untreated_all_players_data"];
    array_push($players_data, get_aggregated_data($data->player));
	$GLOBALS["host_player_data"] = $players_data[0];

    $farmhands = get_farmhands();

    foreach($farmhands as $farmhand) {
        array_push($players_data, get_aggregated_data($farmhand));
    }

	$GLOBALS["all_players_data"] = $players_data;
    return $players_data;
}

function get_aggregated_data(object $data): array {
    $general_data = $GLOBALS["untreated_all_players_data"];
	$GLOBALS["untreated_player_data"] = $data;
    
    return [
        "general" => [
            "id"                    => (int) $data->UniqueMultiplayerID,
            "name"                  => (string) $data->name,
            "gender"                => get_player_gender(),
            "farm_name"             => (string) $data->farmName,
            "farmer_level"          => get_farmer_level(),
            "favorite_thing"        => (string) $data->favoriteThing,
            "pet"                   => get_player_pet(),
            "spouse"                => get_spouse(),
            "children"              => get_children_amount(),
            "house_level"           => get_house_upgrade_level(),
            "date"                  => get_formatted_date(),
            "game_duration"         => get_game_duration(),
            "mine_level"            => (int) $data->deepestMineLevel,
            "max_items"             => (int) $data->maxItems,
            "max_health"            => (int) $data->maxHealth,
            "max_stamina"           => (int) $data->maxStamina,
            "grandpa_score"         => get_grandpa_score(),
            "golds"                 => (int) $data->money,
            "total_golds"           => (int) $data->totalMoneyEarned,
            "golden_walnuts"        => (int) $general_data->goldenWalnutsFound,
            "qi_gems"               => (int) $data->qiGems,
            "casino_coins"          => (int) $data->clubCoins,
            "raccoons"              => (int) $general_data->timesFedRaccoons,
            "stardrops_found"       => get_player_stardrops_found()
        ],
        "levels" => [
            "farming_level"  => (int) $data->farmingLevel,
            "mining_level"   => (int) $data->miningLevel,
            "combat_level"   => (int) $data->combatLevel,
            "foraging_level" => (int) $data->foragingLevel,
            "fishing_level"  => (int) $data->fishingLevel,
        ],
        "unlockables"       => get_player_unlockables_list(),
        "crafting_recipes"  => get_player_crafting_recipes(),
        "books"             => get_player_books(),
        "masteries"         => get_player_masteries(),
        "fish_caught"       => get_player_fishes_caught(),
        "artifacts_found"   => get_player_artifacts(),
        "minerals_found"    => get_player_minerals(),
        "cooking_recipes"   => get_player_cooking_recipes(),
        "shipped_items"     => get_player_shipped_items(),
        "achievements"      => get_player_achievements(),
        "skills"            => get_player_skills_data(),
        "friendship"        => get_player_friendship_data(),
        "enemies_killed"    => get_player_enemies_killed_data(),
        "quest_log"         => get_player_quest_log(),
        "secret_notes"      => get_player_secret_notes(),
        "locations_visited" => get_player_visited_locations()
    ];
}

function get_shared_aggregated_data(): array {
    return [
        "farm_animals"          => get_player_farm_animals(),
        "weather"               => get_weather(),
        "jumino_kart"           => get_junimo_kart_leaderboard(),
        "museum_coords"         => get_museum_pieces_coords(),
        "cc_bundles"            => get_player_bundles()
    ];
}