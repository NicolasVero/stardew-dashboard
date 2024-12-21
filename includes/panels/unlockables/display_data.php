<?php 

function display_unlockables(): string {
    $player_unlockables = get_unlockables_data();
    $images_path = get_images_folder();
	$version_score = $GLOBALS["game_version_score"];
	$decoded_unlockables = $GLOBALS["json"]["unlockables"];
    $unlockables_structure = "";

	foreach($decoded_unlockables as $version => $unlockables) {
        $is_newer_version_class = ($version_score < get_game_version_score($version)) ? "newer-version" : "older-version";
        
		foreach($unlockables as $unlockable) {
			$formatted_name = formate_text_for_file($unlockable);
			if(!isset($player_unlockables[$formatted_name]["is_found"])) {
				continue;
            }
	
			$unlockable_class = ($player_unlockables[$formatted_name]["is_found"]) ? "found" : "not-found";
			$unlockable_image = "$images_path/unlockables/$formatted_name.png";
			$wiki_url = get_wiki_link(get_item_id_by_name($unlockable));
			
			$unlockables_structure .= "
				<span class='tooltip'>
					<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
						<img src='$unlockable_image' class='gallery-item unlockables $unlockable_class $is_newer_version_class' alt='$unlockable'/>
					</a>
					<span>$unlockable</span>
				</span>
			";
		}
	}

    return "
        <section class='gallery unlockables-section _50'>
            <h2 class='section-title'>Unlockables</h2>
            <span>
				<h3 class='no-spoil-title'>" . no_items_placeholder() . "</h3>
                $unlockables_structure
			</span>
		</section>
	";
}