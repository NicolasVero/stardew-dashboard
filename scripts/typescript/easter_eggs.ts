function load_easter_eggs(): void {
    easter_egg_characters();
    easter_egg_kaaris();
}

function easter_egg_characters(): void {
	const characters: string[] = [
		"abigail", "alex", "caroline", "clint", "demetrius", "elliott", "emily",
		"evelyn", "george", "gus", "haley", "harvey", "jas", "jodi", "kent", "leah",
		"lewis", "linus", "marnie", "maru", "pam", "penny", "pierre", "robin",
		"sam", "sandy", "sebastian", "shane", "vincent", "willy", "wizard"
	];

	const date: Date = new Date(); 
	const index_picker: number = [
		new Date(date.getFullYear(), 0, 1).getTime(),
		date.getUTCMonth(),
		date.getUTCDate()
	].reduce((acc, val) => acc * val, 1) % characters.length;

	const character: string = characters[index_picker];
	const elements: NodeListOf<Element> = document.querySelectorAll(".character-name." + character);

	if(elements.length === 0) {
		return;
	}

	const audio: HTMLAudioElement = new Audio(get_site_root() + "/medias/audio/trigger.mp3");
	let is_playing: boolean = false;

	const play_once = (): void => {
		if(!is_playing) {
			is_playing = true;

			const fullscreen_image: HTMLImageElement = document.createElement("img");
			fullscreen_image.src = `https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/medias/images/characters/${character}.png`;
			fullscreen_image.classList.add("fullscreen-image");
			document.body.appendChild(fullscreen_image);

			fullscreen_image.classList.add("show");

			audio.play().finally(() => {
				is_playing = false;
			});

			setTimeout(() => {
				fullscreen_image.classList.remove("show");

				fullscreen_image.addEventListener("transitionend", () => {
					fullscreen_image.remove();
				});
			}, 1000);
		}
	};

	elements.forEach((element: Element) => {
		element.addEventListener("dblclick", play_once);
	});
}

function easter_egg_kaaris(): void {
    const element: HTMLElement = document.querySelector(".house")?.previousElementSibling?.querySelector("img");

    if(element === null) {
        return;
    }
	
    element.classList.add("easter_egg_kaaris");

    const audio: HTMLAudioElement = new Audio(get_site_root() + "/medias/audio/kaaris_maison-citrouille.mp3");
    let is_playing: boolean = false;

    element.addEventListener("dblclick", () => {
        if(!is_playing) {
            is_playing = true;
            audio.play().finally(() => is_playing = false);
        }
    });
}