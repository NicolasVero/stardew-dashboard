<?php

/**
 * Charge la sauvegarde puis affiche le contenu HTML de la page.
 *
 * @param string $save_file Le nom du fichier de sauvegarde à charger (local ou tmp).
 * @param bool $use_ajax Indique s'il s'agit d'une utilisation AJAX ou non.
 * @return mixed : Un tableau contenant les données des joueurs et le HTML de la page si l'utilisation est AJAX, true sinon.
 */
function load_save(string $save_file, bool $use_ajax = true): mixed {
    $uploaded_file = $save_file;
    $raw_data = simplexml_load_file($uploaded_file);
    load_all_json();

    $GLOBALS["raw_xml_data"] = $raw_data;
    $GLOBALS["game_version"] = (string) $raw_data->gameVersion;
    $GLOBALS["shared_players_data"] = get_shared_aggregated_data();

    $players_data = get_all_players_data();
    $GLOBALS["players_names"] = get_players_name();
    $pages["topbar"] = display_topbar();

    for ($player_count = 0; $player_count < count($players_data); $player_count++) {
        $GLOBALS["player_id"] = $player_count;
        $additional_class = ($player_count === 0) ? "host" : "farmhand";
        $pages["player_" . $player_count] = "
            <div class='player_container player_{$player_count}_container $additional_class'>" . 
                display_page() . "
            </div>
        ";
    }

    $pages = ($use_ajax) ? $pages : generate_dev_mode_page($pages);
    
    return [
        "players" => $GLOBALS["players_names"],
        "html" => $pages,
        "code" => "success"
    ];
}

/**
 * Génère le code html de la page en mode développeur
 *
 * @param array Les pages non traitées pour le mode développeur
 * @return string La page générée pour le mode développeur
 */
function generate_dev_mode_page(array $pages): string {
    $dev_mode_page = display_landing_page(false);
        
    foreach ($pages as $page) {
        $dev_mode_page .= $page;
    }
    
    $dev_mode_page .= get_script_loader();
    return $dev_mode_page;
}

/**
 * Cherche et renvoie les joueurs non-hôtes de la sauvegarde à charger.
 *
 * @return array Le nom des joueurs non-hôtes dans la sauvegarde.
 */
function get_farmhands(): array {
    $raw_data = $GLOBALS["raw_xml_data"];
    $all_farmhands = [];

    if (is_game_version_older_than_1_6()) {
        $farmhands = find_xml_tags($raw_data, 'locations.GameLocation.buildings.Building.indoors.farmhand');

        foreach ($farmhands as $farmhand) {
            if ((string) $farmhand->name === "") {
                continue;
            }

            array_push($all_farmhands, $farmhand);
        }
    } else {
        if (empty($raw_data->farmhands)) {
            return [];
        }

        foreach ($raw_data->farmhands->Farmer as $farmhand) {
            if ((string) $farmhand->name === "") {
                continue;
            }

            array_push($all_farmhands, $farmhand);
        }
    }

    return $all_farmhands;
}

/**
 * Récupère et agrège les données de tous les joueurs
 *
 * @return array Un tableau contenant les données agrégées des joueurs.
 */
function get_all_players_data(): array {
    $raw_data = $GLOBALS["raw_xml_data"];
    $farmhands = get_farmhands();
    $GLOBALS["number_of_players"] = 1 + count($farmhands);

    $players_data = [];
    array_push($players_data, get_aggregated_data($raw_data->player));
	$GLOBALS["host_player_data"] = $players_data[0];

    if (!empty($farmhands)) {
        foreach ($farmhands as $farmhand) {
            array_push($players_data, get_aggregated_data($farmhand));
        }
    }

    $GLOBALS["players_data"] = $players_data;
    return $players_data;
}

/**
 * Agrège les données d'un joueur.
 *
 * @param object $player_raw_data Les données brutes du joueur issues de la sauvegarde.
 * @return array Les données agrégées du joueur.
 */
function get_aggregated_data(object $player_raw_data): array {
    $raw_data = $GLOBALS["raw_xml_data"];
	$GLOBALS["current_player_raw_data"] = $player_raw_data;
    
    return [
        "general" => [
            "id"                    => (int) $player_raw_data->UniqueMultiplayerID,
            "name"                  => (string) $player_raw_data->name,
            "gender"                => get_player_gender(),
            "farm_name"             => (string) $player_raw_data->farmName,
            "farmer_level"          => get_farmer_level(),
            "favorite_thing"        => (string) $player_raw_data->favoriteThing,
            "pet"                   => get_player_pet(),
            "spouse"                => get_spouse(),
            "children"              => get_children_amount(),
            "house_level"           => get_house_upgrade_level(),
            "date"                  => get_formatted_date(false),
            "formatted_date"        => get_formatted_date(),
            "game_duration"         => get_game_duration(),
            "mine_level"            => (int) $player_raw_data->deepestMineLevel,
            "max_items"             => (int) $player_raw_data->maxItems,
            "max_health"            => (int) $player_raw_data->maxHealth,
            "max_stamina"           => (int) $player_raw_data->maxStamina,
            "grandpa_score"         => get_grandpa_score(),
            "golds"                 => (int) $player_raw_data->money,
            "total_golds"           => (int) $player_raw_data->totalMoneyEarned,
            "golden_walnuts"        => (int) $raw_data->goldenWalnutsFound,
            "qi_gems"               => (int) $player_raw_data->qiGems,
            "casino_coins"          => (int) $player_raw_data->clubCoins,
            "raccoons"              => (int) $raw_data->timesFedRaccoons,
            "stardrops_found"       => get_player_stardrops_found(),
            "tools"                 => get_player_tools()
        ],
        "levels" => [
            "farming_level"  => (int) $player_raw_data->farmingLevel,
            "mining_level"   => (int) $player_raw_data->miningLevel,
            "combat_level"   => (int) $player_raw_data->combatLevel,
            "foraging_level" => (int) $player_raw_data->foragingLevel,
            "fishing_level"  => (int) $player_raw_data->fishingLevel,
        ],
        "unlockables"       => get_player_unlockables_list(),
        "crafting_recipes"  => get_player_crafting_recipes(),
        "books"             => get_player_books(),
        "masteries"         => get_player_masteries(),
        "fish_caught"       => get_player_fish_caught(),
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

/**
 * Agrège les données partagées des joueurs.
 *
 * @return array Les données agrégées partagées des joueurs.
 */
function get_shared_aggregated_data(): array {
    return [
        "farm_animals"          => get_player_farm_animals(),
        "weather"               => get_weather(),
        "jumino_kart"           => get_junimo_kart_leaderboard(),
        "museum_coords"         => get_museum_pieces_coords(),
        "cc_bundles"            => get_player_bundles(),
        "farm_informations"     => get_farm_informations_data()
    ];
}
