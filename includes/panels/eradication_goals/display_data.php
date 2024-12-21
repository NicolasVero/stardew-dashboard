<?php 

function display_monster_eradication_goals_panel(): string {
	$player_id = get_current_player_id();
    $images_path = get_images_folder();
    $goals_data = get_player_adventurers_guild_data($player_id);
    $goals = "";

    foreach($goals_data as $goal_data) {
        if(is_bool($goal_data)) {
            continue;
        }

        extract($goal_data);
        extract($reward);

        $wiki_link = get_wiki_link(get_item_id_by_name($alt));
        $is_found = ($counter < $limit) ? "not-found" : "found";
        $total = ($is_completed) ? $counter : "$counter/$limit";
        $is_completed_icon = ($is_completed) ? "<img src='$images_path/content/goal_star.png' class='star' alt=''/>" : "";
        $reward_icon = "
            <span class='tooltip' style='display: flex;'>
                <a href='$wiki_link' class='wiki_link' rel='noreferrer' target='_blank'>
                    <img src='$images_path/rewards/$src.png' class='reward $is_found always-on-display' alt='$alt'/>
                </a>
                <span>$alt</span>
            </span>
        ";

        $goals .= "<span class='goal'>$reward_icon $total $target $is_completed_icon</span>";
    }

    return "
        <section class='monster-eradication-goals-$player_id panel monster-eradication-goals-panel modal-window'>
            <span class='header'>
                <span class='title'>
                    <span>Monster Eradication Goals</span>
                    <span>Help us keep the valley safe.</span>
                </span>
                <img src='$images_path/content/dashes.png' class='dashes' alt=''/>
                <img src='$images_path/icons/exit.png' class='exit-monster-eradication-goals exit-monster-eradication-goals-$player_id exit' alt='Exit'/>
            </span>
            <span class='goals'>
                $goals
            </span>
        </section>
    ";
}
