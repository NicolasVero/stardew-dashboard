const gallery_option = document.querySelector(".settings-panel .gallery-order");

gallery_option.addEventListener("change", (event) => {
    const target = event.target as HTMLSelectElement;

    if(target.value === "alphabetical-order") {
        gallery_alphabetic_order_display();
    }

    if(target.value === "discovery-level") {
        gallery_discovery_order_display();
    }

    if(target.value === "version") {
        gallery_reset_order();
    }

    initialize_tooltips(null, true);
});

function gallery_reset_order() {
    const galleries_items_containers = document.querySelectorAll(".gallery-items-container");

    galleries_items_containers.forEach((container) => {
        const tooltips_list = container.querySelectorAll(".tooltip");

        tooltips_list.forEach((item: HTMLElement) => {
            item.style.removeProperty("order");
        });
    });
}

function gallery_alphabetic_order_display() {
    const galleries_items_containers = document.querySelectorAll(".gallery-items-container");

    galleries_items_containers.forEach((container) => {
        const tooltips_list = container.querySelectorAll(".tooltip");
        const items_list = container.querySelectorAll(".tooltip img.gallery-item");
        const items_names = Array.from(items_list).map((element) => element.getAttribute("alt")).sort();
    
        tooltips_list.forEach((item: HTMLElement) => {
            const image_alt = item.querySelector("img.gallery-item").getAttribute("alt");
            item.style.order = items_names.indexOf(image_alt).toString();
        });
    });
}

function gallery_discovery_order_display() {
    const galleries_items_containers = document.querySelectorAll(".gallery-items-container");

    galleries_items_containers.forEach((container) => {
        const tooltips_list = container.querySelectorAll(".tooltip");
        
        tooltips_list.forEach((item: HTMLElement) => {
            const item_state = item.querySelector("img.gallery-item").className;

            if(item_state.includes("found")) {
                item.style.order = "0";
            }

            if(item_state.includes("unused")) {
                item.style.order = "1";
            }

            if(item_state.includes("not-found")) {
                item.style.order = "2";
            }
        });
    });
}