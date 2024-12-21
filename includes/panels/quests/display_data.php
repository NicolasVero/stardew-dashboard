<?php 

function display_quest_button(): string {
	return "<img src='" . get_images_folder() . "/icons/quest_icon.png' class='quest-icon view-all-quests view-all-quests-" . get_current_player_id() . " button-elements modal-opener icon' alt='Quest icon'/>";
}

function display_quest_panel(): string {
	$player_id = get_current_player_id();
	$this_player_data = get_quest_log_data();
    $images_path = get_images_folder();
    $quest_structure = "";

	if(empty($this_player_data)) {
        return "
            <section class='all-quests-$player_id panel quests-panel modal-window'>
                <div class='panel-header'>
                    <h2 class='section-title panel-title'>Quests in progress</h2>
                    <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'/>
                </div>
                <span class='quests'>
					<h3>" . no_items_placeholder() . "</h3>
				</span>
			</section>
        ";
	}

    foreach($this_player_data as $data) {
		extract($data);

        $quest_structure .= "
            <span class='quest'>
                <span class='quest-infos'>
                    <span class='quest-description'>$objective</span>
                    <span class='quest-title'>$title</span>
                </span>
        ";

        if(empty($rewards)) {
			$quest_structure .= "</span>";
			continue;
		}
        
		if(isset($daysLeft)) {
			$day_text = ($daysLeft > 1) ? "days" : "day";
			$quest_structure .= " <span class='days-left'><img src='$images_path/icons/timer.png' alt='Time left'/>$daysLeft $day_text</span>";
		}

		$quest_structure .= "<span class='quest-rewards'>";
		
        for($i = 0; $i<count($rewards); $i++) {
			// Reward tooltip (pas besoin pourgold and qi gems)
            $quest_structure .= ((is_numeric($rewards[$i]) || str_ends_with($rewards[$i], 'q'))) ? "<span class='quest-reward'>" : "<span class='quest-reward tooltip'>";
            
			/*
            if Friendship hearts/points
			elseif Gold
			elseif Qi Gems
			elseif something else
            */
            if(strstr($rewards[$i], "Friendship")) {
                $reward_number = explode(" ", $rewards[$i])[0];
                $quest_structure .= "<img src='$images_path/rewards/heart_$reward_number.png' alt='Friendship reward'/>";
            } elseif(is_numeric($rewards[$i])) {
                $quest_structure .= formate_number($rewards[$i]) . "<img src='$images_path/rewards/gold.png' alt='Gold coins reward'/>";
            } elseif(str_ends_with($rewards[$i], 'q')) {
                $quest_structure .= explode('_', $rewards[$i])[0] . "<img src='$images_path/rewards/qi_gem.png' alt='Qi gems reward'/>";
            } else {
                $quest_structure .= $rewards[$i];
            }

            $quest_structure .= (is_numeric($rewards[$i])) ? "" : "<span>$rewards[$i]</span>";
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
                <h2 class='section-title panel-title'>Quests in progress</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-quests-$player_id exit' alt='Exit'/>
            </div>
            <span class='quests'>
                $quest_structure
            </span>
        </section>
    ";
}