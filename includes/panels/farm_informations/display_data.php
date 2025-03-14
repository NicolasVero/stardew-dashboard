<?php

/**
 * Génère le code HTML pour afficher le bouton des informations de la ferme.
 *
 * @return string Le code HTML du bouton des informations de la ferme.
 */
function display_farm_informations_button(): string {
    $version_class = get_version_class("1.6.0");

	return "<img src='" . get_images_folder() . "/icons/farm_computer.png' class='$version_class-icon farm-informations-icon view-farm-informations view-farm-informations-" . get_current_player_id() . " button-elements modal-opener icon' alt='Farm informations icon'/>";
}

/**
 * Génère le code HTML du panneau des outils du joueur.
 * 
 * @return string Le code HTML du panneau des outils du joueur.
 */
function display_farm_informations(): string {
	$farm_informations = get_farm_informations_data();
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $informations = "";

	foreach($farm_informations as $caption => $count) {
		$informations .= "<span class='farm-info'>" . __($caption) . " : " . __($count) . "</span>";
	}

    return "
        <section class='farm-informations-$player_id panel farm-informations modal-window'>
            <span class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Farm informations") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-farm-informations-$player_id exit' alt='Exit'/>
            </span>
            <span class='farm-infos'>
                $informations
            </span>
        </section>
    ";
}