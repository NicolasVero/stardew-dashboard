<?php

/**
 * Génère le code HTML du header du site.
 * 
 * @return string Le code HTML du header.
 */
function display_header(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
	$general_data = get_general_data();
	$festival_icon = display_festival_icon();
    $weather_icon = display_weather_icon();
    
    extract($general_data); //? all field "general" in extract_data_from_save.php

    $pet_icon = $pet['type'] . "_" . $pet['breed'];
	$farm_name = str_contains(strtolower($farm_name), "farm") ? $farm_name : $farm_name . " " . __("farm");

    return "
        <header>
            <div class='header'>
                <span class='player'>
                    <img src='$images_path/icons/$pet_icon.png' alt='Pet type'>
                    <img src='$images_path/icons/" . strtolower($gender) . ".png' class='player-gender-logo' alt='Gender logo: $gender'>
                    <span class='data player-name'>$name<span class='data-label farmer-level'> " . __("$farmer_level") . " " . __("at") . " $farm_name</span></span>
                </span>

                <span class='date'>
                    $weather_icon
                    <span class='data date-in-game view-calendar-$player_id modal-opener'>$formatted_date</span>
                    $festival_icon
                </span>

                <span class='game-time'>
                    <span class='data time-in-game'>$game_duration</span>
                    <span class='data-label'>" . __("time in game") . "</span>
                </span>
            </div>

            <div class='sub-header'>
                <span class='all-money'>" 
                    .
                    display_stat([
                        "icon" => "Gold coins", "value" => $golds, "wiki_link" => "Gold"
                    ])
                    .
                    display_stat([
                        "icon" => "Golden Walnuts", "value" => $golden_walnuts, "wiki_link" => "Golden_Walnut", "tooltip" => "$golden_walnuts / 130 " . __("golden walnuts found")
                    ])
                    .
                    display_stat([
                        "icon" => "Qi gems", "value" => $qi_gems, "wiki_link" => "Qi_Gem"
                    ])
                    .
                    display_stat([
                        "icon" => "Casino coins", "value" => $casino_coins, "wiki_link" => "Casino"
                    ])
                . "</span>
                <span class='perfection-stats'> ".
                    display_stat([
                        "icon" => "Grandpa", "alt" => "GrandPa candles", "label" => "candles lit", "value" => get_candles_lit($grandpa_score), "wiki_link" => "Grandpa", "tooltip" => __("Number of candles lit on the altar") . " ($grandpa_score " . __("points") . ")"
                    ])
                    .
                    display_stat([
                        "icon" => "Stardrop", "alt" => "Perfection", "label" => "perfection progression", "value" => get_perfection_percentage() . "%", "wiki_link" => "Perfection"
                    ])
                . "</span>
            </div>
        </header>
    ";
}

/**
 * Génère le code HTML de l'icône de la météo.
 * 
 * @return string Le code HTML de l'icône de la météo.
 */
function display_weather_icon(): string {
    $shared_data = $GLOBALS["shared_players_data"];
    $images_path = get_images_folder();
    $weather = $shared_data["weather"];

    return "
        <span class='tooltip'>
            <a href='" . __("https://stardewvalleywiki.com/Weather") . "' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$images_path/icons/$weather.png' class='weather_icon' alt='Weather icon'>
            </a>
            <span class='left'>" . get_weather_tooltip($weather) . "</span>
        </span>
    ";
}

/**
 * Génère le code HTML de l'icône du festival.
 * 
 * @return string Le code HTML de l'icône du festival.
 */
function display_festival_icon(): string {
    $images_path = get_images_folder();
    $festivals = sanitize_json_with_version("festivals", true);
	$festival_name = __("Not a festival day");
	$festival_class = "isnt_festival";

	foreach ($festivals as $key => $festival) {
		for ($i = 0; $i < count($festival["date"]); $i++) {
			if (!is_this_the_same_day($festival["date"][$i])) {
                continue;
			}
            
			$festival_name = $festival["name"];
			$festival_class = "is_festival";
			$wiki_link = get_wiki_link($key);
            $festival_icon = "$images_path/icons/festival_icon.gif";
			break;
		}
	}
    
    if (!isset($wiki_link)) {
        $wiki_link = get_wiki_link_by_name("festival");
        $festival_icon = "$images_path/icons/festival_icon.png";
    }

    return "
        <span class='tooltip'>
            <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$festival_icon' class='festival_icon $festival_class' alt='Festival icon'>
            </a>
            <span class='right'>" . __($festival_name) . "</span>
        </span>
    ";
}
