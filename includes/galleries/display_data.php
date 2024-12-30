<?php 

function display_detailled_gallery_title(string $section_title, array $panel_details): string {
    $player_id = get_current_player_id();
    if(empty($panel_details)) {
        return "<h2 class='section-title'>" . __($section_title) . "</h2>";
    }

    extract($panel_details); //? $panel_alt, $panel_name
    return "
        <span class='has_panel'>
            <h2 class='section-title'>" . __($section_title) . "</h2>
            <span class='view-$panel_alt view-$panel_alt-$player_id modal-opener'>- " . __("View $panel_name") . "</span>
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
            $element_tooltip = get_tooltip_text($player_data, $json_line_name, $json_filename);

			$item_structure .= "
				<span class='tooltip'>
					<a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$element_image' class='gallery-item $json_filename $element_class $version_class' alt='$json_line_name'/>
                    </a>
                    <span>" . __($element_tooltip) . "</span>
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

function get_detailled_gallery_image(string $json_filename, string $json_line_name): string {
	
	$images_path = get_images_folder();

	if(!in_array($json_filename, ["secret_notes"])) {
		return "$images_path/$json_filename/" . formate_text_for_file($json_line_name). ".png";
	}

	$line_name = explode(" ", $json_line_name);
	$icon_name = formate_text_for_file(implode(" ", array_slice($line_name, 0, 2)));
	return "$images_path/icons/$icon_name.png";
}

function get_detailled_gallery_wiki_link(string $json_filename, string $json_line_name): string {
	if(in_array($json_filename, ["achievements", "secret_notes"])) {
		$wiki_url = [
			"achievements" => get_wiki_link_by_name("achievements"),
			"secret_notes" => get_wiki_link_by_name("secret_notes")
		][$json_filename];
	} else {
		$wiki_url = get_wiki_link(get_item_id_by_name($json_line_name));
	}

	return $wiki_url;
}