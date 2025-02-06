<?php

/**
 * Affiche le titre détaillé de la galerie avec un lien pour l'aperçu d'un panneau.
 * 
 * @param string $section_title Le titre de la section.
 * @param array $panel_details Les détails du panneau.
 * @return string Le titre de la section.
 */
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

/**
 * Affiche la galerie détaillée avec les images et les liens associés aux éléments.
 * 
 * @param array $gallery_details Les détails de la galerie.
 * @param string $width_class La classe de largeur de la galerie.
 * @param array $panel_details Les détails du panneau.
 * @return string La galerie détaillée.
 */
function display_detailled_gallery(array $gallery_details, string $width_class = "", array $panel_details = []): string {
    extract($gallery_details); //? $player_data, $json_filename, $section_title
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
        <section class='gallery $json_filename-section $width_class'>
            $title
            <span>
				<h3 class='no-spoil-title'>" . no_items_placeholder() . "</h3>
                $item_structure
            </span>
		</section>
    ";
}

/**
 * Récupère le lien d'image pour un élément de la galerie détaillée.
 * 
 * @param string $json_filename Le nom du fichier JSON.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @return string Le lien de l'image.
 */
function get_detailled_gallery_image(string $json_filename, string $json_line_name): string {
	
	$images_path = get_images_folder();

	if(!in_array($json_filename, ["secret_notes"])) {
		return "$images_path/$json_filename/" . format_text_for_file($json_line_name). ".png";
	}

	$line_name = explode(" ", $json_line_name);
	$icon_name = format_text_for_file(implode(" ", array_slice($line_name, 0, 2)));
	return "$images_path/icons/$icon_name.png";
}

/**
 * Récupère le lien vers la page wiki d'un élément de la galerie détaillée.
 * 
 * @param string $json_filename Le nom du fichier JSON.
 * @param string $json_line_name Le nom de la ligne JSON.
 * @return string Le lien de la page wiki.
 */
function get_detailled_gallery_wiki_link(string $json_filename, string $json_line_name): string {
	if($json_filename === "achievements") {
		return get_wiki_link_by_name("achievements");
	}
	
	if($json_filename === "secret_notes") {
		// Renvoie secret_notes || journal_scraps 
		$json_line_name = implode(" ", array_slice(explode(" ", $json_line_name), 0, 2)) . "s";
		$formatted_name = format_text_for_file($json_line_name);
		return get_wiki_link_by_name($formatted_name);
	}

	return get_wiki_link(get_item_id_by_name($json_line_name));
}