let current_section: HTMLElement | null = null;

function activate_buttons(show: string, hide: string, sections_to_show: string): void {
    const show_button: NodeListOf<HTMLElement> = document.querySelectorAll(show);
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement = document.querySelector(sections_to_show);

    show_button.forEach((button) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            if(sections !== null) {
                current_section = sections;
                toggle_visibility(sections, true);

                if(!sections.hasAttribute('data-tooltips-initialized')) {
                    initialize_tooltips(sections.classList[0]);
                    sections.setAttribute('data-tooltips-initialized', 'true');
                }
            }
        });
    });

    hide_button.forEach((button) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            current_section = null;
        });
    });
}

function activate_close_buttons(hide: string, sections_to_hide: string): void {
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement = document.querySelector(sections_to_hide);

    hide_button.forEach((button) => {
        button.addEventListener("click", () => {
            if(sections !== null) {
                sections.remove();
                current_section = null;
            }
        });
    });
}

function hide_all_sections(section_destroy: boolean = false): void {
	const sections: NodeListOf<HTMLElement> = document.querySelectorAll(".modal-window");

	sections.forEach((section) => {
		if(section.classList.contains("to-destroy") && section_destroy) {
			section.remove();
		}

		section.style.display = "none";
	});
}