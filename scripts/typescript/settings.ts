function get_settings(): { no_spoil: boolean, toggle_versions: boolean, spoil: boolean } {
    return {
        no_spoil: (document.getElementById("no_spoil_mode") as HTMLInputElement).checked,
        toggle_versions: (document.getElementById("toggle_versions_items_mode") as HTMLInputElement).checked,
        spoil: (document.getElementById("spoil_mode") as HTMLInputElement).checked
    };
}

function initialize_settings(): void {
    handle_toggle_versions_mode();
    handle_no_spoil_mode();
    handle_spoil_mode();
};

function handle_no_spoil_mode(): void {
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;

    if(no_spoil_checkbox !== null && spoil_checkbox !== null && no_spoil_checkbox.checked && spoil_checkbox.checked) {
        spoil_checkbox.checked = false;
    }

    update_display(["not-found", "found"]);
};

function handle_toggle_versions_mode(): void {
    update_display("newer-version");
};

function handle_spoil_mode(): void {
    const no_spoil_checkbox = document.getElementById("no_spoil_mode") as HTMLInputElement;
    const spoil_checkbox = document.getElementById("spoil_mode") as HTMLInputElement;

    if(no_spoil_checkbox === null || spoil_checkbox === null) {
        return;
    }

    if(spoil_checkbox.checked && no_spoil_checkbox.checked) {
        no_spoil_checkbox.checked = false;
        update_display(["not-found", "found"]);
    } else {
        update_display(["found"]);
    }
};

function handle_steam_mode(): void {
    const images_folder: string[] = ["steam_achievements", "achievements"];
    const images: NodeListOf<Element> = document.querySelectorAll(".achievements-section img");

    images.forEach((image: Element) => {
        const src: string = image.getAttribute("src");
        const [old_folder, new_folder] = (src.includes('steam')) ? images_folder : [...images_folder].reverse();
        image.setAttribute("src", src.replace(old_folder, new_folder));
    });
}