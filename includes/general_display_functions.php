<?php

function display_panels(): string {
	$structure  = display_friendships();
	$structure .= display_quest_panel();
    $structure .= display_visited_locations_panel();
	$structure .= display_monster_eradication_goals_panel();
	$structure .= display_calendar_panel();
	$structure .= display_farm_animals_panel();
	$structure .= display_junimo_kart_panel();
	$structure .= display_museum_panel();
	$structure .= display_community_center_panel();
    
    return $structure;
}

function display_detailled_gallery_title(string $section_title, array $panel_details): string {
    $player_id = get_current_player_id();
    if(empty($panel_details)) {
        return "<h2 class='section-title'>$section_title</h2>";
    }

    extract($panel_details); //? $panel_alt, $panel_name
    return "
        <span class='has_panel'>
            <h2 class='section-title'>$section_title</h2>
            <span class='view-$panel_alt view-$panel_alt-$player_id modal-opener'>- View $panel_name</span>
        </span>
    ";

}


function display_detailled_gallery(array $gallery_details, string $width = "", array $panel_details = []): string {
    extract($gallery_details); //? $player_data, $json_filename, $section_title
	$images_path = get_images_folder();
    $json_data = $GLOBALS["json"][$json_filename];
    $title = display_detailled_gallery_title($section_title, $panel_details);

    $item_structure = "";
    
    foreach($json_data as $version => $json_version) {
        $version_class = get_version_class($version); 

        foreach($json_version as $json_line_name) {
            $is_found = array_key_exists($json_line_name, $player_data);
            $element_class = get_found_classes($player_data, $json_filename, $json_line_name, $is_found);
            $element_image = get_detailled_gallery_image($json_filename, $json_line_name);
            $wiki_url = get_detailled_gallery_wiki_link($json_filename, $json_line_name);
            $element_tooltip = ($is_found) ? get_tooltip_text($player_data, $json_line_name, $json_filename) : $json_line_name;

			$item_structure .= "
				<span class='tooltip'>
					<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$element_image' class='gallery-item $json_filename $element_class $version_class' alt='$json_line_name'/>
                    </a>
                    <span>$element_tooltip</span>
                </span>
            ";
        }
    }

    return "
        <section class='gallery $json_filename-section $width'>
            $title
            <span>
				<h3 class='no-spoil-title'>" . no_items_placeholder() . "</h3>
                $item_structure
            </span>
		</section>
    ";
}

function display_project_contributor(array $options): string {
    extract($options); //? $name, $icon, $texts, $socials

    $images_path = get_images_folder();
    $portrait =  "$images_path/content/$icon.png";
    $presentation = "";
    $socials_links = "";

    foreach($texts as $text) {
        $presentation .= "<span>$text</span>";
    }

    foreach($socials as $social_name => $social) {
        extract($social); //? $url, $on_display
        if($on_display) {
            $socials_links .= "<a href='$url' rel='noreferrer' target='_blank'><img src='$images_path/social/$social_name.png' alt='$social_name'/></a>";
        }
    }

    return "
        <span>
            <img src='$portrait' class='character-image $icon' alt='$name'/>
            <span>
                <span class='character-presentation'>
                    $presentation
                </span>
                <span class='socials'>
                    $socials_links
                </span>
            </span>
        </span>
    ";
}