<?php

/**
 * Génère le code HTML pour afficher le bouton de Junimo Kart.
 *
 * @return string Le code HTML du bouton de Junimo Kart.
 */
function display_junimo_kart_button(): string {
	return "<img src='" . get_images_folder() . "/icons/controller.png' class='controller-icon view-junimo-kart-leaderboard view-junimo-kart-leaderboard-" . get_current_player_id() . " button-elements modal-opener icon' alt='Controller icon'>";
}

/**
 * Génère le code HTML pour afficher le panneau de Junimo Kart.
 *
 * @return string Le code HTML du panneau de Junimo Kart.
 */
function display_junimo_kart_panel(): string {
    $raw_data = $GLOBALS["raw_xml_data"];
    $player_id = get_current_player_id();
    $images_path = get_images_folder();
    $junimo_structure = "";

    $raw_scores = get_verified_jk_leaderboard($raw_data->junimoKartLeaderboards->entries);
    $counter = 1;

    foreach ($raw_scores->NetLeaderboardsEntry as $raw_score) {
        if ($counter > 5) {
            break;
        }

        $name = (string) $raw_score->name->string;
        $score = (int) $raw_score->score->int;
        $leader_class = ($counter === 1) ? "leader" : "";
        $junimo_structure .= "
            <span class='record-holder $leader_class'>
                <span class='record-holder-details'>
                    <span class='record-holder-counter'>#$counter</span>
                    <span class='record-holder-name'>$name</span>
                </span>
                <span class='record-holder-score'>$score</span>
            </span>
        ";
        $counter++;
    }

    return "
        <section class='junimo-kart-leaderboard-$player_id panel junimo-kart-leaderboard-panel modal-window'>
            <span class='leaderboard'>
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-junimo-kart-leaderboard-$player_id' alt='Exit'>
                <img src='$images_path/content/junimo_kart.png' class='image-title' alt='Junimo Kart Background'>
                <span class='scores'>
                    $junimo_structure
                </span>
            </span>
        </section>
    ";
}
