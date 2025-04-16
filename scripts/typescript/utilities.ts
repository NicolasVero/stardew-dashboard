function get_site_root(): string { 
    const protocol: string = window.location.protocol;
    const host: string = window.location.host;

    return (host === "localhost") 
        ? `${protocol}//localhost/travail/stardew_dashboard` 
        : `${protocol}//stardew-dashboard.42web.io`;
}

async function get_max_upload_size(): Promise<number> {
    return fetch("./functions.php?action=get_max_upload_size")
    .then((response: Response) => response.json()) 
    .then((data: { post_max_size: number }) => {
        return data.post_max_size;
    });
}

function in_bytes_conversion(size: string): number {
    const unit_to_power: { [key: string]: number } = { "o": 0, "Ko": 1, "Mo": 2, "Go": 3 };
    const matches: RegExpMatchArray = size.match(/(\d+)([a-zA-Z]+)/);

    if (!matches) {
        throw new Error("Invalid size format");
    }

    const value: number = parseInt(matches[1], 10);
    const unit: string = matches[2];

    return value * Math.pow(1024, unit_to_power[unit]);
}

function toggle_visibility(element: HTMLElement, should_display: boolean): void {
    element.style.display = (should_display) ? "block" : "none";
}

function get_current_player_id(): number | null {
    const visible_player: Element = Array.from(document.querySelectorAll(".player_container"))
        .find((player: Element) => window.getComputedStyle(player).display === "block");

    if (visible_player !== null && visible_player !== undefined) {
        const match: RegExpMatchArray = visible_player.className.match(/player_(\d+)_container/);
        return match ? parseInt(match[1], 10) : null;
    }

    return null;
}

function get_players_number(): number | null {
    const players_container: HTMLElement = document.querySelector('#players_selection');

    if (players_container !== null) {
        const players_number: number = players_container.getElementsByTagName('li').length;
        return (players_number === 0) ? 1 : players_number;
    }

    return null;
}

function get_deletabled_settings_panels(): string[] {
    return [".feedback-panel"];
}

function get_closabled_settings_panels(): string[] {
    return [".upload-panel", ".settings-panel", ".languages-panel"];
}

function get_settings_panels(): string[] {
    return [...get_closabled_settings_panels(), ...get_deletabled_settings_panels()];
}

function close_all_panels(panel_selectors: string[], include_setting_panels: boolean = false): void {
    const settings_panels: string[] = (include_setting_panels) ? get_settings_panels() : [];
    const player_id: number | null = get_current_player_id();

    if (player_id === null) {
        panel_selectors = settings_panels;
    } else {
        panel_selectors.push(...settings_panels);
    }
    
    panel_selectors.forEach((panel_base: string) => {
        const id: string = settings_panels.includes(panel_base) ? "" : "-" + player_id;
        const panel_selector: string = panel_base + id;

        const panel: HTMLElement = document.querySelector(panel_selector);

        if (panel !== null) {
            panel.style.display = "none";

            if (get_deletabled_settings_panels().includes(panel_selector)) {
                panel.remove();
            }
        }
    });
}

function can_close_panel(event: Event): boolean {
    return (
        current_section
        && event.target instanceof HTMLElement
        && event.target !== current_section
        && !current_section.contains(event.target)
        && !event.target.classList.contains("modal-opener")
        && !current_section.classList.contains("to-keep-open")
    );
}

function toggle_loading(shown: boolean): void {
    const loading_strip: HTMLElement = document.querySelector("#loading-strip");

    if (loading_strip !== null) {
        loading_strip.style.display = (shown) ? "block" : "none";
    }
}

function get_parent_element(element: HTMLElement | null): HTMLElement | null {
    if (element === null) {
        return null;
    }

    const parent: HTMLElement = element.parentElement;
    return parent?.classList.contains("wiki_link") ? parent.parentElement : parent;
};

function set_element_display(element: HTMLElement, show: boolean): void {
    if (element !== null && element.className !== "locations") {
        element.style.display = (show) ? "flex" : "none";
    }
};

function has_class(element: HTMLElement, class_name: string): boolean {
    return element.classList.contains(class_name);
};

function is_section_empty(section: HTMLElement): boolean {
    const spans: NodeListOf<HTMLElement> = section.querySelectorAll(".tooltip");
    return Array.from(spans).every((span: HTMLElement) => span.style.display === "none");
};

function has_section_older_version_items(section: HTMLElement): boolean {
    return Array.from(section.querySelectorAll("img")).some((img: HTMLImageElement) => 
        has_class(img, "older-version")
    );
};

function should_show_element(element: HTMLElement, settings: Settings): boolean {
    const is_newer: boolean = has_class(element, "newer-version") ||has_class(element, "newer-version-icon");
    const is_not_found: boolean = has_class(element, "not-found");
    const should_keep_on_display: boolean = has_class(element, "always-on-display");
    const is_found: boolean = has_class(element, "found");
    const is_not_hide: boolean = has_class(element, "not-hide");

    if (is_not_hide) return true;
    if (settings.toggle_versions && is_newer) return false;
    if (settings.no_spoil && is_not_found && !should_keep_on_display) return false;
    if (settings.spoil && is_found) return false;
    
    return true;
};

function toggle_landing_page(display: boolean): void {
    const landing_page: HTMLElement = document.getElementById("landing_page");

    if (landing_page !== null) {
        landing_page.style.display = (display) ? "block" : "none";
    }
}

function save_landing_topbar(): void {
	const landing_menu: HTMLElement = document.getElementById("landing_menu");

	if (landing_menu !== null) {
		const topbar = landing_menu.innerHTML;
	}
}
