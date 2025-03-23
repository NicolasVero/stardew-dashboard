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


galleries_items_containers.forEach((container) => {
    const tooltips_list = container.querySelectorAll(".tooltip");
    
    // console.log(tooltips_list)
    tooltips_list.forEach((item: HTMLElement) => {
        const item_state = item.querySelector("img.gallery-item").className;
        console.log(item_state);

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