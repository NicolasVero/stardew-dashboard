let current_section: HTMLElement = null;

function activate_buttons(show: string, hide: string, sections_to_show: string): void {
    const show_button: NodeListOf<HTMLElement> = document.querySelectorAll(show);
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement = document.querySelector(sections_to_show);

    show_button.forEach((button: HTMLElement) => {
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

    hide_button.forEach((button: HTMLElement) => {
        button.addEventListener("click", () => {
            hide_all_sections(true);
            current_section = null;
        });
    });
}

function activate_close_buttons(hide: string, sections_to_hide: string): void {
    const hide_button: NodeListOf<HTMLElement> = document.querySelectorAll(hide);
    const sections: HTMLElement = document.querySelector(sections_to_hide);

    hide_button.forEach((button: HTMLElement) => {
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
	sections.forEach((section: HTMLElement) => {
		if(section.classList.contains("to-destroy") && section_destroy) {
			section.remove();
		}

		section.style.display = "none";
	});
}

function prevent_panel_scroll(): void {
    const modals: NodeListOf<HTMLDivElement> = document.querySelectorAll<HTMLDivElement>(".modal-window");
    
    modals.forEach((modal: HTMLDivElement) => {
        modal.addEventListener(
            "wheel",
            (event: WheelEvent) => {
                const scroll_top: number = modal.scrollTop; 
                const scroll_height: number = modal.scrollHeight;
                const client_height: number = modal.clientHeight;
    
                const is_at_top: boolean = scroll_top === 0 && event.deltaY < 0;
                const is_at_bottom: boolean = scroll_top + client_height >= scroll_height && event.deltaY > 0;
    
                if(is_at_top || is_at_bottom) {
                    event.preventDefault();
                }
            },
            { passive: false }
        );
    });
}

function unlock_loading_exception(): void {
    toggle_loading(false);
    hide_all_sections();
}

function error_popup(): string {
    const section: HTMLElement = document.createElement("section");
    section.classList.add("error-popup", "panel", "modal-window", "to-destroy", "medium-panel-width", "limit-panel-height", "panel-with-border");
    
    const header: HTMLDivElement = document.createElement("div");
    header.classList.add("panel-header");

    const title: HTMLHeadingElement = document.createElement("h2");
    title.classList.add("section-title", "panel-title");
    title.innerText = "Error";

    const closeImg: HTMLImageElement = document.createElement("img");
    closeImg.src = get_site_root() + "/assets/images/icons/exit.png";
    closeImg.classList.add("exit-error-popup", "exit");
    closeImg.alt = "Exit";

    header.appendChild(title);
    header.appendChild(closeImg);

    const span: HTMLSpanElement = document.createElement("span");
    span.classList.add("error-message");
    span.innerText = "An unknown error occurred. Please try again later or with another save.";
    
    section.appendChild(header);
    section.appendChild(span);

    return section.outerHTML;
}