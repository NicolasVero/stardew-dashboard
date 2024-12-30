<?php 

function display_skills(): string {
    $images_path = get_images_folder();
	$player_skills = get_skills_data();
	$player_skills_levels = get_levels_data();
	$player_masteries = get_masteries_data();
    $skill_structure = "";

    $mastery_visible_class = (empty($player_masteries)) ? "" : "not-hide";

    foreach($player_skills_levels as $key => $level) {
        $level_icon_name = explode('_', $key)[0];
        $mastery_class   = (array_key_exists(ucfirst(explode('_', $key)[0]) . " Mastery", $player_masteries)) ? 'found' : 'not-found';
        $mastery_tooltip = ucfirst(explode('_', $key)[0]) . " mastery";
        $is_newer_version_class = (is_game_older_than_1_6()) ? "newer-version" : "older-version";

        $skill_structure .= "
            <span class='skill $key'>
                <span class='tooltip'>
                    <a href='" . get_wiki_link_by_name("mastery_cave") . "' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$images_path/skills/mastery.png' class='level-icon $mastery_class $mastery_visible_class $is_newer_version_class' alt='$key'/>
                    </a>
                    <span>" . __($mastery_tooltip) . "</span>
                </span>
        
                <span class='tooltip'>
                    <a href='" . get_wiki_link_by_name("skills") . "#" . ucfirst($level_icon_name) . "' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$images_path/skills/$level_icon_name.png' class='level-icon' alt='$key'/>
                    </a>
                    <span>" . ucfirst($level_icon_name) . "</span>
                </span>
                " . get_level_progress_bar($level) . "
                <span class='level data'>$level</span>
                <span>
                    <a href='" . get_wiki_link_by_name("skills") . "' class='wiki_link' rel='noreferrer' target='_blank'>" 
                        . get_skills_icons($player_skills, $level_icon_name) . "
                    </a>
                </span>
            </span>
        ";
    }

    return "
		<section class='skills-section info-section'>
			<h2 class='section-title'>" . __("Skills") . "</h2>
            <span>
                $skill_structure
            </span>
        </section>
    ";
}