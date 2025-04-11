<?php

/**
 * Génère le code HTML de la galerie des éléments débloqués par le joueur.
 * 
 * @return string Le code HTML de la galerie des éléments débloqués par le joueur.
 */
function display_unlockables(): string {
    $player_unlockables = get_unlockables_data();
    $images_path = get_images_folder();
	$version_score = get_game_version_score($GLOBALS["game_version"]);
	$decoded_unlockables = $GLOBALS["json"]["unlockables"];
    $unlockables_structure = "";

	foreach($decoded_unlockables as $version => $unlockables) {
        $is_newer_version_class = ($version_score < get_game_version_score($version)) ? "newer-version" : "older-version";
        
		foreach($unlockables as $unlockable) {
			$formatted_name = format_text_for_file($unlockable);
			if (!isset($player_unlockables[$formatted_name]["is_found"])) {
				continue;
            }
	
			$unlockable_class = ($player_unlockables[$formatted_name]["is_found"]) ? "found" : "not-found";
			$unlockable_image = "$images_path/unlockables/$formatted_name.png";
			$wiki_link = get_wiki_link(get_item_id_by_name($unlockable));
			
			$unlockables_structure .= "
				<span class='tooltip'>
					<a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
						<img src='$unlockable_image' class='gallery-item unlockables $unlockable_class $is_newer_version_class' alt='" . __($unlockable) . "'>
					</a>
					<span>" . __($unlockable) . "</span>
				</span>
			";
		}
	}

    return "
        <section class='gallery unlockables-section _50'>
            <h2 class='section-title'>" . __("Unlockables") . "</h2>
            <span class='gallery-items-container'>
				<h3 class='no-spoil-title'>" . no_items_placeholder() . "</h3>
                $unlockables_structure
			</span>
		</section>
	";
}