const panels: Record<string, string> = {
    Digit1: ".visited-locations",
    Digit2: ".monster-eradication-goals",
    Digit3: ".junimo-kart-leaderboard",
    Digit4: ".all-quests",
    Digit5: ".all-friends",
    Digit6: ".calendar",
    Digit7: ".all-animals",
    Digit8: ".museum",
    Digit9: ".community-center",
    Digit0: ".farm-informations",
    Minus : ".tools"
};
const all_panels: string[] = Object.values(panels); 

window.addEventListener("keydown", (event: KeyboardEvent) => {
    if (event.code === "Escape") {
        close_all_panels(all_panels, true);
    } 

    if (panels[event.code]) {
        const panel_selector: string = panels[event.code] + "-" + get_current_player_id();
        const panel: HTMLElement = document.querySelector(panel_selector);
        const panel_display: string = (panel?.style.display === "block") ? "none" : "block";
        
        close_all_panels(all_panels);
        panel.style.display = panel_display;
    }
});

window.addEventListener("click", (event: MouseEvent) => {
    if (can_close_panel(event)) {
        close_all_panels(all_panels, true);
    }
});
