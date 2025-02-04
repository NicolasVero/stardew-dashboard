<?php 

function display_farm_animals(): string {
    $gallery_details = [
        "player_data" => get_farm_animals_data(),
        "json_filename" => "farm_animals",
        "section_title" => "Farm animals"
    ];
    $panel_details = [
        "panel_alt"     => "all-animals",
        "panel_name"    => "all farm animals"
    ];
    return display_detailled_gallery($gallery_details, "", $panel_details);
}

function display_farm_animals_panel(): string {
	$player_id = get_current_player_id();
    $animals_friendship = get_farm_animals_data();
    $images_path = get_images_folder();
    $farm_animals_structure = "";

    if(empty($animals_friendship)) {
        return "
            <section class='all-animals-$player_id panel all-animals-panel modal-window'>
                <div class='panel-header'>
                    <h2 class='section-title panel-title'>" . __("Farm animals friendships") . "</h2>
                    <img src='$images_path/icons/exit.png' class='exit-all-animals-$player_id exit' alt='Exit'/>
                </div>
                <span class='friendlist'>
			        <h3>" . no_items_placeholder() . "</h3>
                </span>
            </section>
        ";
    }

    foreach($animals_friendship as $animal_friendship) {
        extract($animal_friendship); //? $id, $animals_data, $counter

        foreach($animals_data as $animal_data) {
            extract($animal_data); //? $name, $type, $friendship_level, $happiness, $was_pet

            $formatted_type = format_text_for_file($type);
            $wiki_url = get_wiki_link($id);
            $animal_icon = "$images_path/farm_animals/$formatted_type.png";
            $pet_class = ($was_pet) ? "pet" : "not-petted";
            $pet_tooltip = ($was_pet) ? __("Caressed by the auto-petter") : __("No auto-petter in this building");
            $status = ($happiness > 200) ? "happy" : (($happiness > 30) ? "fine" : "sad");
            $status_icon = "$images_path/icons/{$status}_emote.png";


            $hearts_html = "";
            $max_heart = 5;
            for($i = 1; $i <= $max_heart; $i++) {
                $heart_icon = 
                (($friendship_level >= $i) ?
                    "heart.png" :
                        (($friendship_level === ($i - 0.5)) ?
                            "half_heart.png" : "empty_heart.png"));
                $hearts_html .= "<img src='$images_path/icons/$heart_icon' class='hearts' alt=''/>";
            }

            $farm_animals_structure .= "
                <span>
                    <a href='$wiki_url' class='wiki_link' rel='noreferrer' target='_blank'>
                        <img src='$animal_icon' class='animal-icon' alt='$type icon'/>
                    </a>
                    <span class='animal-name'>$name</span>
                    <span class='hearts-level'>$hearts_html</span>
                    <span class='interactions'>
                        <span class='tooltip'>
                            <img src='$images_path/icons/pet.png' class='interaction $pet_class' alt=''/>
                            <span>$pet_tooltip</span>
                        </span>
                        <span class='tooltip'>
                            <img src='$status_icon' class='status' alt='$status'/>
                            <span>" . get_animal_status_tooltip($status, $name) . "</span>
                        </span>
                    </span>
                </span>
            ";
        }
    }

    return "
        <section class='all-animals-$player_id panel all-animals-panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Farm animals friendships") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-all-animals-$player_id exit' alt='Exit'/>
            </div>
            <span class='friendlist'>
                $farm_animals_structure
            </span>
        </section>
    ";
}