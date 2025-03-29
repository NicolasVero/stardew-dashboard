interface Settings {
    toggle_versions: boolean;
    no_spoil: boolean;
    spoil: boolean;
}

function update_section_visibility(section: HTMLElement, settings: Settings): void {
    const title: HTMLHeadingElement = section.querySelector("h2");
    const smaller_title: HTMLElement = section.children[1]?.querySelector("span .no-spoil-title") as HTMLElement;
    const is_empty: boolean = is_section_empty(section);
    const has_older_items: boolean = has_section_older_version_items(section);

    if (settings.toggle_versions && is_empty && !has_older_items) {
        section.classList.add("hidden");
        return;
    }

    section.classList.remove("hidden");

    if (title !== null) {
        title.style.display = "block";
    }

    if (smaller_title !== null) {
        let should_show_smaller_title: boolean = false;

        if (settings.no_spoil) {
            should_show_smaller_title = is_empty;
        } else if (settings.toggle_versions) {
            should_show_smaller_title = is_empty && has_older_items;
        } else {
            should_show_smaller_title = is_empty;
        }

        smaller_title.style.display = should_show_smaller_title ? "block" : "none";
    }
}

function update_display(target_classes: string[]): void {
    const settings: Settings = get_settings();

    const update_elements = (class_name: string) => {
        const elements: HTMLCollectionOf<Element> = document.getElementsByClassName(class_name);

        if (class_name.split("-").pop() === "icon") {
            Array.from(elements).forEach((element: HTMLElement) => {
                if (element !== null) {
                    set_element_display(element, should_show_element(element, settings));
                }
            });
        } else {
            Array.from(elements).forEach((element: HTMLElement) => {
                const parent: HTMLElement = get_parent_element(element);
    
                if (parent !== null) {
                    set_element_display(parent, should_show_element(element, settings));
                }
            });
        }
    };

    target_classes.forEach(update_elements);
    const sections: HTMLCollectionOf<Element> = document.getElementsByClassName("gallery");
    
    Array.from(sections).forEach((section: HTMLElement) => 
        update_section_visibility(section, settings)
    );
};
