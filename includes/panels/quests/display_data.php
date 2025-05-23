<?php

/**
 * Génère le code HTML du bouton pour ouvrir le panneau des quêtes.
 *
 * @return string Le code HTML du bouton pour ouvrir le panneau des quêtes.
 */
function display_quest_button(): string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='quest-icon view-all-quests view-all-quests-" . get_current_player_id() . " button-elements modal-opener icon' alt='Quest icon'>";
}

/**
 * Génère le code HTML du panneau des quêtes.
 *
 * @return string Le code HTML du panneau des quêtes.
 */
function display_quest_panel(): string {
	$player_id = get_current_player_id();
	$player_quests = get_quest_log();
    $images_path = get_images_folder();
    $quest_structure = "";

    if (empty($player_quests)) {
        return "
            <section class='all-quests-$player_id panel quests-panel modal-window'>
                <div class='panel-header'>
                    <h2 class='section-title panel-title'>" . __("Quests in progress") . "</h2>
                    <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'>
                </div>
                <span class='quests'>
                    " . no_items_placeholder() . "
                </span>
            </section>
        ";
    }

    foreach ($player_quests as $quest) {
		extract($quest); //? $time_limited, $objective, $description, $title, $rewards

        $quest_structure .= "
            <span class='quest'>
                <span class='quest-infos'>
                    <span class='quest-description'>" . __($objective) . "</span>
                    <span class='quest-title'>" . __($title) . "</span>
                </span>
        ";

        if (empty($rewards)) {
			$quest_structure .= "</span>";
			continue;
		}
        
		if (isset($days_left)) {
			$day_text = ($days_left > 1) ? "days" : "day";
			$quest_structure .= " <span class='days-left'><img src='$images_path/icons/timer.png' alt='Time left'>$days_left " . __($day_text) . "</span>";
		}

		$quest_structure .= "<span class='quest-rewards'>";
		
        for ($i = 0; $i < count($rewards); $i++) {
			// Reward tooltip (pas besoin pourgold and qi gems)
            $quest_structure .= ((is_numeric($rewards[$i]) || $rewards[$i] === null || str_ends_with($rewards[$i], 'q'))) ? "<span class='quest-reward'>" : "<span class='quest-reward tooltip'>";
            
			/*
            Plusieurs types de rewards :
            Friendship hearts/points
			Gold
			Qi Gems
			Objects (string)
            */
            if (strstr($rewards[$i], "Friendship")) {
                $reward_number = explode(" ", $rewards[$i])[0];
                $quest_structure .= "<img src='$images_path/rewards/heart_$reward_number.png' alt='Friendship reward'>";
            } elseif (is_numeric($rewards[$i])) {
                $quest_structure .= format_number($rewards[$i], $GLOBALS["site_language"]) . "<img src='$images_path/rewards/gold.png' alt='Gold coins reward'>";
            } elseif (str_ends_with($rewards[$i], 'q')) {
                $quest_structure .= explode('_', $rewards[$i])[0] . "<img src='$images_path/rewards/qi_gem.png' alt='Qi gems reward'>";
            } else {
                if ($rewards[$i] === null) {
                    continue;
                }
                
                $quest_structure .= __($rewards[$i]);
            }

            $quest_structure .= (is_numeric($rewards[$i]) || $rewards[$i] === null) ? "" : "<span>" . __($rewards[$i]) . "</span>";
            $quest_structure .= "</span>";
        }

        $quest_structure .= "
                </span>
            </span>
        ";
    }

    return "
        <section class='all-quests-$player_id panel quests-panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Quests in progress") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'>
            </div>
            <span class='quests'>
                $quest_structure
            </span>
        </section>
    ";
}
