function update_tooltips_after_ajax(): void {
    on_images_loaded(() => {
        initialize_tooltips();
    });
}

function initialize_tooltips(section: string = null, is_overload: boolean = false): void {
    let tooltips: NodeListOf<HTMLElement>;
    
    if (section === null || section === '') {
        tooltips = document.querySelectorAll(".tooltip");
    } else {
        tooltips = document.querySelector("." + section).querySelectorAll(".tooltip");
    }

    tooltips.forEach((tooltip: HTMLElement) => {
        const rect: DOMRect = tooltip.getBoundingClientRect();
        const span: HTMLElement = tooltip.querySelector("span");

        if (span && (!["left", "right"].some((className: string) => span.classList.contains(className)) || is_overload)) {
            if(rect.left === 0) {
                return;
            }

            const tooltip_position: string = (rect.left < window.innerWidth / 2) ? "right" : "left";
            
            if (is_overload) {
                span.classList.remove("left");
                span.classList.remove("right");
            }

            span.classList.add(tooltip_position);
        }
    });
}

function on_images_loaded(callback: () => void): void {
    let images_loaded: number = 0;
    const images: NodeListOf<HTMLImageElement> = document.querySelectorAll("img");
    const total_images: number = images.length;

    if (total_images === 0) {
        callback();
        return;
    }

    const increment_and_check = () => {
        images_loaded++;
        if (images_loaded === total_images) {
            callback();
        }
    };

    images.forEach((image: HTMLImageElement) => {
        if (image.complete) {
            increment_and_check();
        } else {
            image.addEventListener("load", increment_and_check);
            image.addEventListener("error", increment_and_check);
        }
    });

    if (images_loaded === total_images) {
        callback();
    }
}
