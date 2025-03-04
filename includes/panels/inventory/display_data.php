<?php

/**
 * Génère le code HTML du panneau des outils du joueur.
 * 
 * @return string Le code HTML du panneau des outils du joueur.
 */
function display_player_tools(): string {
	if(empty($player_tools = get_player_tools())) {
		return "";
	}

	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $tools = "";

    foreach($player_tools as $player_tool) {
		$formatted_name = format_text_for_file($player_tool);
        $tools .= "
			<span class='tool'>
				<img src='$images_path/tools/$formatted_name.png' class='tool-icon' alt='$player_tool'/>
				$player_tool
			</span>
		";
    }

    return "
        <section class='tools-$player_id panel tools-panel modal-window'>
            <span class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Tools") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'/>
            </span>
            <span class='tools'>
                $tools
            </span>
        </section>
    ";
}