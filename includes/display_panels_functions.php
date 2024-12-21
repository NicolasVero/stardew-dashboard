<?php











function display_junimo_kart_panel(): string {
    $untreated_data = $GLOBALS["untreated_all_players_data"];
    $player_id = get_current_player_id();
    $images_path = get_images_folder();
    $junimo_structure = "";

    $untreated_scores = get_junimo_leaderboard($untreated_data->junimoKartLeaderboards->entries);
    $counter = 1;

    foreach($untreated_scores->NetLeaderboardsEntry as $untreated_score) {
        if($counter > 5) {
            break;
        }

        $name = (string) $untreated_score->name->string;
        $score = (int) $untreated_score->score->int;
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
                <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-junimo-kart-leaderboard-$player_id' alt='Exit'/>
                <img src='$images_path/content/junimo_kart.png' class='image-title' alt='Junimo Kart Background'/>
                <span class='scores'>
                    $junimo_structure
                </span>
            </span>
        </section>
    ";
}




if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "display_feedback_panel") {
    require "utility_functions.php";
	echo display_feedback_panel();
}