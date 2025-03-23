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
