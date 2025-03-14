<?php

/**
 * Génère le code HTML pour afficher le bouton des outils.
 *
 * @return string Le code HTML du bouton des outils.
 */
function display_player_tools_button(): string {
    if(!is_game_singleplayer()) {
        return "";
    }
	return "<img src='" . get_images_folder() . "/icons/tools.png' class='tools-icon view-tools view-tools-" . get_current_player_id() . " button-elements modal-opener icon' alt='Tools icon'/>";
}

/**
 * Génère le code HTML du panneau des outils du joueur.
 * 
 * @return string Le code HTML du panneau des outils du joueur.
 */
function display_player_tools(): string {
	if(empty($player_tools = get_tools_data())) {
		return "";
	}

	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $tools = "";

    foreach($player_tools as $category => $player_tool) {
        $formatted_category = explode("/", $category)[1];
		$formatted_name = format_text_for_file($player_tool);
        $tools .= "
			<span class='tool'>
                <span class='tool-category'>" . __($formatted_category) . "</span>
				<img src='$images_path/tools/$formatted_name.png' class='tool-icon' alt='$player_tool'/>
				<span class='tool-name'> " . __($player_tool) . "</span>
			</span>
		";
    }

    return "
        <section class='tools-$player_id panel tools modal-window'>
            <span class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Tools") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-tools-$player_id exit' alt='Exit'/>
            </span>
            <span class='tools'>
                $tools
            </span>
        </section>
    ";
}