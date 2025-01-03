<?php 

function display_topbar(bool $is_landing_page = false, bool $is_error_screen = false): string {
	$menu_id = ($is_landing_page) ? "landing_menu" : (($is_error_screen) ? "error_menu" : "dashboard_menu");
	$save_id = ($is_landing_page) ? "landing" : "file";
    $languages_id = ($is_landing_page) ? "landing" : "a_changer";
	$settings_id = ($is_landing_page) ? "landing" : "main";
    $player_selection = (!$is_landing_page && !$is_error_screen) ? display_player_selection() : "";
	$game_version = (!$is_landing_page && !$is_error_screen) ? display_game_version() : "";
	$home_button = (!$is_landing_page && !$is_error_screen) ? display_home_button() : "";

    return "
        <div id='$menu_id' class='topbar'>
            $player_selection
            <span>
                $game_version
                " . display_save_button($save_id) . "
                " . display_languages_button($languages_id) . "
                " . display_settings_button($settings_id) . "
                " . display_feedback_button() . "
                $home_button
            </span>
        </div>
    ";
}

function display_save_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='upload-panel panel to-keep-open modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Upload a save") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-upload exit' alt='Exit'/>
            </div>
            <span>
                <span>
                    <label id='browse-files' for='save-upload'>" . __("Browse") . "</label>
                    <span id='new-filename'>" . __("Choose a file") . "</span>
                    <input type='file' id='save-upload'>
                </span>
            </span>
        </section>
    ";
}

function display_languages_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='languages-panel panel modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Choose language") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-languages exit' alt='Exit'/>
            </div>
            <span>
                " . display_all_languages_button() . " 
            </span>
        </section>
    ";
}

function display_settings_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='settings-panel panel settings modal-window'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Settings") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-settings exit' alt='Exit'/>
            </div>
            <span class='checkboxes'>
                <span class='checkbox'>
                    <input type='checkbox' id='spoil_mode'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='spoil_mode' id='spoil-label'>" . __("Hide discovered items") . "</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='no_spoil_mode'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='no_spoil_mode' id='no-spoil-label'>" . __("Hide undiscovered items") . "</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='steam_achievements'>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='steam_achievements' id='steam_achievements-label'>" . __("Show Steam achievements icons") . "</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='toggle_versions_items_mode' checked>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='toggle_versions_items_mode' id='toggle-versions-items-label'>" . __("Hide items from newer versions") . "</label>
                </span>
                <span class='checkbox'>
                    <input type='checkbox' id='wiki_redirections' checked>
                    <span class='checkmark'><img src='$images_path/icons/checked.png' alt=''/></span>
                    <label for='wiki_redirections' id='wiki_redirections-label'>" . __("Activate wiki redirections") . "</label>
                </span>
            </span>
        </section>
    ";
}

function display_feedback_panel(): string {
    $images_path = get_images_folder();
    return "
        <section class='feedback-panel panel modal-window to-destroy'>
            <div class='panel-header'>
                <h2 class='section-title panel-title'>" . __("Your feedback") . "</h2>
                <img src='$images_path/icons/exit.png' class='exit-feedback exit' alt='Exit'/>
            </div>
            <span>
                <form id='feedback_form'>
                    <span>
                        <span class='label_and_input'>
                            <label for='username'>" . __("Username") . "</label>
                            <input type='text' id='username' name='username' required>
                        </span>

                        <span class='label_and_input mail_input'>
                            <label for='mail'>" . __("Email address") . "</label>
                            <input type='email' id='mail' name='mail' required>
                        </span>
                    </span>

                    <span class='label_and_input full_width'>
                        <label>" . __("Topic") . "</label>

                        <span class='topic_selection'>
                            <span>
                                <input type='radio' id='feature_request' value='Feature request' name='topic' class='feedback_real_radio' required checked>
                                <img src='$images_path/icons/feature.png' class='feedback_custom_radio' alt='Feature request topic'/>
                                <label for='feature_request'>" . __("Feature request") . "</label>
                            </span>

                            <span>
                                <input type='radio' id='bug_report' value='Bug report' name='topic' class='feedback_real_radio'>
                                <img src='$images_path/icons/bug.png' class='feedback_custom_radio topic_not_selected' alt='Bug report topic'/>
                                <label for='bug_report'>" . __("Bug report") . "</label>
                            </span>

                            <span>
                                <input type='radio' id='other' value='Other' name='topic' class='feedback_real_radio'>
                                <img src='$images_path/icons/other.png' class='feedback_custom_radio topic_not_selected' alt='Other topic'/>
                                <label for='other'>" . __("Other") . "</label>
                            </span>
                        </span>
                    </span>

                    <span class='label_and_input full_width'>
                        <label for='message'>" . __("Message") . "</label>
                        <textarea rows='8' id='message' name='message' required></textarea>
                    </span>

                    <input type='submit' value='" . __("Send feedback") . "'>
                </form>
            </span>
        </section>
    ";
}

function display_player_selection(): string {
    $players_names = $GLOBALS["players_names"];
    $players_name_structure = "";

    if(count($players_names) > 1) {
        for($i = 0; $i < count($players_names); $i++) {
            $players_name_structure .= "<li class='player_selection' value='player_$i'>" . formate_usernames($players_names[$i]) . "</option>";
        }
    }

    return "
        <ul id='players_selection'>
            $players_name_structure
        </ul>
    ";
}

function display_game_version(): string {
    return "<span class='game_version'>V " . $GLOBALS["game_version"] . "</span>";
}

function display_settings_button(string $prefix): string {
    return "
        <span class='$prefix-settings modal-opener'>
            <img src='" . get_images_folder() . "/icons/settings.png' class='modal-opener' alt='Settings icon'/>
        </span>
    ";
}

function display_languages_button(string $prefix): string {
    $language = get_site_language();
    return "
        <span class='$prefix-languages modal-opener'>
            <img src='" . get_images_folder() . "/languages/$language.png' class='modal-opener' alt='Language= icon'/>
        </span>
    ";
}

function display_save_button(string $prefix): string {
    return "
        <span class='$prefix-upload modal-opener'>
            <img src='" . get_images_folder() . "/icons/file.png' class='modal-opener' alt='File upload icon'/>
        </span>
    ";
}

function display_feedback_button(): string {
    return "
        <span class='feedback-opener modal-opener'>
            <img src='" . get_images_folder() . "/icons/feedback.png' class='modal-opener' alt='Feedback icon'/>
        </span>
    ";
}

function display_home_button(): string {
    return "
        <span class='landing-page-opener'>
            <img src='" . get_images_folder() . "/icons/home.png' id='home-icon' alt='Home icon'/>
        </span>
    ";
}

function display_all_languages_button(): string {
    $languages = get_supported_languages();

    // log_($languages);

    return "";
}