<?php 

function display_community_center_button(): string {
	return "<img src='" . get_images_folder() . "/icons/golden_scroll.png' class='golden-scroll-icon view-community-center view-community-center-" . get_current_player_id() . " button-elements modal-opener icon' alt='Golden Scroll icon'/>";
}

function display_community_center_panel(): string {
    $player_id = get_current_player_id();
    $player_bundles = $GLOBALS["shared_players_data"]["cc_bundles"];
    $bundles_json = sanitize_json_with_version("bundles", true);
    $images_path = get_images_folder();
	$cc_binary = get_cc_binary_hash($player_bundles);
    $cc_structure = "";
    
    foreach($bundles_json as $room_name => $room_details) {
        if($room_name === "Bulletin Board" && has_element_in_mail("JojaMember")) {
            continue;
        }

        $cc_structure .= "
            <span class='room'>
                <h2>$room_name</h2>
                <span class='bundles'>
        ";

        foreach($room_details["bundle_ids"] as $bundle_id) {
            $bundle_details = $player_bundles[$bundle_id];
            $bundle_name = $bundle_details["bundle_name"];
            $formatted_bundle_name = formate_text_for_file($bundle_name);

            if($bundle_details["is_complete"]) {
                $is_complete_class = "complete";
                $bundle_tooltip_class = "";
                $bundle_tooltip = "";
            } else {
                $is_complete_class = "incomplete";
                $bundle_tooltip_class = "bundle-tooltip tooltip";

                $required_items = display_bundle_requirements($bundle_details["requirements"], $bundle_details["items_added"]);
                $slots = ($bundle_details["room_name"] === "Vault") ? display_bundle_purchase() : display_bundle_added_items($bundle_details["items_added"], $bundle_details["limit"]);

                $bundle_tooltip = "
                    <span>
                        <img src='$images_path/content/bundle_bg.png' class='bundle-bg' alt='Bundle background'/>
                        <img src='$images_path/bundles/{$formatted_bundle_name}_bundle.png' class='bundle-icon' alt='$bundle_name Bundle'/>
                        <span class='required-items'>
                            $required_items
                        </span>
                        <span class='slots'>
                            $slots
                        </span>
                    </span>
                ";
            }
            
            
            $cc_structure .= "
                <span class='bundle $bundle_tooltip_class'>
                    <img src='$images_path/bundles/{$formatted_bundle_name}_bundle.png' class='$is_complete_class' alt='$bundle_name Bundle'/>
                    $bundle_tooltip
                </span>
            ";
        }

        $cc_structure .= "
                </span>
            </span>
        ";
    }

    return "
        <section class='community-center-$player_id panel community-center-panel modal-window'>
            <img src='$images_path/icons/exit.png' class='absolute-exit exit exit-community-center-$player_id' alt='Exit'/>
            <div class='community-center-background-container'>
                <img src='$images_path/bundles/CC_$cc_binary.png' class='background-image' alt='Community center background'/>
                <img src='$images_path/icons/chevron_down.png' class='chevron-down' alt='Scroll indicator'/>
            </div>
            <span class='rooms'>
                $cc_structure
            </span>
        </section>
    ";
}

function display_bundle_requirements(array $requirements, array $added_items): string {
    $images_path = get_images_folder();
    $structure = "";
    
    foreach($requirements as $requirement) {
        extract($requirement);

        $formatted_item_name = formate_text_for_file($name);
        $has_been_donated = (has_been_donated_in_bundle($name, $added_items)) ? "donated" : "not-donated";
        $quantity = ($quantity > 1) ? "<span class='quantity'>$quantity</span>" : "";

        $structure .= "
            <span class='required-item'>
                <img src='$images_path/$type/$formatted_item_name.png' class='item $has_been_donated' alt='$name'/>
                <img src='$images_path/icons/quality_$quality.png' class='quality' alt=''/>
                $quantity
            </span>
        ";
    }

    return $structure;
}

function display_bundle_added_items(array $added_items, int $limit): string {
    $structure = "";
    $images_path = get_images_folder();
    
    for($i = 0; $i < $limit; $i++) {
        $added_item = "";

        if(isset($added_items[$i])) {
            $item_name = $added_items[$i]["name"];
            $formatted_item_name = formate_text_for_file($item_name);
            $type = $added_items[$i]["type"];
            $added_item = "<img src='$images_path/$type/$formatted_item_name.png' class='added-item' alt='$item_name'/>";
        }

        $structure .= "
            <span class='slot'>
                <img src='$images_path/icons/bundle_slot.png' class='empty-slot' alt=''/>
                $added_item
            </span>
        ";
    }

    return $structure;
}

function display_bundle_purchase(): string {
    return "<img src='" . get_images_folder() . "/content/purchase.png' class='purchase' alt=''/>";
}