let topbar: HTMLElement | null;

window.addEventListener("load", () => {
    const os_path: string = get_os_path(detect_os());
    const tag: HTMLElement = document.getElementById("save_os_path");
    
    if(tag !== null) {
        tag.innerHTML = os_path;
    }
    
    const toggle_versions_items_mode: HTMLElement = document.getElementById("toggle_versions_items_mode");
    const no_spoil_mode: HTMLElement = document.getElementById("no_spoil_mode");
    const spoil_mode: HTMLElement = document.getElementById("spoil_mode");
    const steam_achievements: HTMLElement = document.getElementById("steam_achievements");
    
    if(toggle_versions_items_mode !== null) {
        toggle_versions_items_mode.addEventListener("change", handle_toggle_versions_mode);
    }
    
    if(no_spoil_mode !== null) {
        no_spoil_mode.addEventListener("change", handle_no_spoil_mode);
    }

    if(spoil_mode !== null) {
        spoil_mode.addEventListener("change", handle_spoil_mode);
    }

    if(steam_achievements !== null) {
        steam_achievements.addEventListener("change", handle_steam_mode);
    }

    const save_upload: HTMLElement = document.getElementById("save-upload");
    if(save_upload !== null) {
        save_upload.addEventListener("change", file_choice);
    }

    save_landing_topbar();
    activate_buttons(".landing-upload", ".exit-upload", ".upload-panel");
    activate_buttons(".landing-settings", ".exit-settings", ".settings");
    toggle_custom_checkboxes(".checkmark");
    activate_feedback_ajax_trigger();
});
