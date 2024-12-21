<?php








function get_level_progress_bar(int $level, int $max_level = 10): string {
    $images_path = get_images_folder();
    $level_structure = "";
    
    for($i = 1; $i <= $max_level; $i++) {
        $state = ($level >= $i) ? "" : "_empty";
        $icon_type = ($i % ($max_level / 2) === 0) ? "big_level" : "level";
        $level_bar = $images_path . "/icons/{$icon_type}{$state}.png";
        
        $level_structure .= "<img src='$level_bar' alt=''/>";
    }
    
    return "
        <span class='level-progress-bar'>
            $level_structure
        </span>
    ";
}

function get_skills_icons(array $skills, string $current_skill): string {
    $images_path = get_images_folder();
    $skill_structure = "";

    foreach($skills as $skill) {
        if($current_skill === strtolower($skill["source"])) {

            $skill_icon = strtolower($skill["skill"]);
            $skill_icon_path = "$images_path/skills/$skill_icon.png";
            $skill_description = $skill["description"];

            $skill_structure .= "
                <span class='tooltip'>
                    <img src='$skill_icon_path' alt='$skill_description'/>
                    <span>$skill_description</span>
                </span>
			";
        }
    }

    return "
        <div class='skills-section'>
            $skill_structure
        </div>
    ";
}












function display_project_contributor(array $options): string {
    extract($options);

    $images_path = get_images_folder();
    $portrait =  "$images_path/content/$icon.png";
    $presentation = "";
    $socials_links = "";

    foreach($texts as $text) {
        $presentation .= "<span>$text</span>";
    }

    foreach($socials as $social_name => $social) {
        extract($social);
        if($on_display) {
            $socials_links .= "<a href='$url' rel='noreferrer' target='_blank'><img src='$images_path/social/$social_name.png' alt='$social_name'/></a>";
        }
    }

    return "
        <span>
            <img src='$portrait' class='character-image $icon' alt='$name'/>
            <span>
                <span class='character-presentation'>
                    $presentation
                </span>
                <span class='socials'>
                    $socials_links
                </span>
            </span>
        </span>
    ";
}

