<?php 

function display_header(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
	$all_players_data = get_general_data();
	$festival_icon = display_festival_icon();
    $weather_icon = display_weather_icon();
    
    extract($all_players_data);  

    $pet_icon = $pet['type'] . "_" . $pet['breed'];
	$farm_name = str_contains(strtolower($farm_name), "farm") ? $farm_name : $farm_name . " farm";

    return "
        <header>
            <div class='header'>
                <span class='player'>
                    <img src='$images_path/icons/$pet_icon.png' alt='Pet type'/>
                    <img src='$images_path/icons/" . strtolower($gender) . ".png' class='player_gender_logo' alt='Gender logo: $gender'/>
                    <span class='data player_name'>" . formate_usernames($name) . "<span class='data-label'> $farmer_level at $farm_name</span></span>
                </span>

                <span class='date'>
                    $weather_icon
                    <span class='data date-in-game view-calendar-$player_id modal-opener'>$date</span>
                    $festival_icon
                </span>

                <span class='game_time'>
                    <span class='data time-in-game'>$game_duration</span>
                    <span class='data-label'>time in game</span>
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
                        "icon" => "Golden Walnuts", "value" => $golden_walnuts, "wiki_link" => "Golden_Walnut", "tooltip" => "$golden_walnuts / 130 golden walnuts found"
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
                        "icon" => "Grandpa", "alt" => "GrandPa candles", "label" => "candles lit", "value" => get_candles_lit($grandpa_score), "wiki_link" => "Grandpa", "tooltip" => "Number of candles lit on the altar ($grandpa_score points)"
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

function display_weather_icon(): string {
    $data = $GLOBALS["shared_players_data"];
    $images_path = get_images_folder();
    $weather = $data["weather"];

    return "
        <span class='tooltip'>
            <a href='https://stardewvalleywiki.com/Weather' class='wiki_link' rel='noreferrer' target='_blank'>
                <img src='$images_path/icons/$weather.png' class='weather_icon' alt='Weather icon'/>
            </a>
            <span class='left'>" . get_weather_tooltip($weather) . "</span>
        </span>
    ";
}

function display_festival_icon(): string {
    $images_path = get_images_folder();
    $festivals = sanitize_json_with_version("festivals", true);
	$festival_name = "Not a festival day";
	$festival_class = "isnt_festival";

	foreach($festivals as $key => $festival) {
		for($i = 0; $i < count($festival["date"]); $i++) {
			if(is_this_the_same_day($festival["date"][$i])) {
				$festival_class = "is_festival";
				$festival_name = $festival["name"];
				$wiki_url = get_wiki_link($key);
				break;
			}
		}
	}

	return (isset($wiki_url)) 
    ? 
	"<span class='tooltip'>
		<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
			<img src='$images_path/icons/festival_icon.gif' class='festival_icon $festival_class' alt='Festival icon'/>
		</a>
		<span class='right'>$festival_name</span>
	</span>"
	:
	"<span class='tooltip'>
        <a href='" . get_wiki_link_by_name("festival") . "' class='wiki_link' rel='noreferrer' target='_blank'>
		    <img src='$images_path/icons/festival_icon.png' class='festival_icon $festival_class' alt='Festival icon'/>
		</a>
        <span class='right'>$festival_name</span>
	</span>";
}
