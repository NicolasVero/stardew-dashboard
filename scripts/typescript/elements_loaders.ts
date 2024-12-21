function load_error_page_items(): void {
    const button_configurations = [
        { open_button: ".main-settings", exit_button: ".exit-settings", modal_panel: ".settings"     },
        { open_button: ".file-upload",   exit_button: ".exit-upload",   modal_panel: ".upload-panel" }
    ];

    button_configurations.forEach(({ open_button, exit_button, modal_panel }) => {
        activate_buttons(open_button, exit_button, modal_panel);
    });
}

function load_elements(): void {
    toggle_landing_page(false);
    toggle_checkboxes_actions();

    const common_buttons = [
        { open_button: ".landing-settings", exit_button: ".exit-settings", modal_panel: ".settings"     },
        { open_button: ".landing-upload"  , exit_button: ".exit-upload"  , modal_panel: ".upload-panel" },
        { open_button: ".main-settings"   , exit_button: ".exit-settings", modal_panel: ".settings"     },
        { open_button: ".file-upload"     , exit_button: ".exit-upload"  , modal_panel: ".upload-panel" }
    ];

    const dynamic_prefixes: string[] = [
        "all-friends", "all-quests", "monster-eradication-goals",
        "calendar", "all-animals", "junimo-kart-leaderboard",
        "museum", "community-center", "visited-locations"
    ];

    const players_in_save: number = get_players_number();
    const dynamic_buttons = [];
	
    for(let i: number = 0; i < players_in_save; i++) {
        dynamic_prefixes.forEach((prefix: string) => {
            dynamic_buttons.push({
                open_button: `.view-${prefix}-${i}`,
                exit_button: `.exit-${prefix}-${i}`,
                modal_panel: `.${prefix}-${i}`
            });
        });
    }
    
    const all_buttons = [...common_buttons, ...dynamic_buttons];

    all_buttons.forEach(({ open_button, exit_button, modal_panel }) => {
        activate_buttons(open_button, exit_button, modal_panel);
    });

    document.getElementById("home-icon")?.addEventListener("click", () => {
        const display: boolean = document.getElementById("landing_page")?.style.display !== "none";
        toggle_landing_page(!display);
    });

    load_easter_eggs();
    update_tooltips_after_ajax();
}