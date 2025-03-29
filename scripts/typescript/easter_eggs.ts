function load_easter_eggs(): void {
    easter_egg_characters();
    easter_egg_kaaris();
	easter_egg_monarchy_mayhem();
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

	const audio: HTMLAudioElement = new Audio(get_site_root() + "/assets/audio/trigger.mp3");
	let is_playing: boolean = false;

	const play_once = (): void => {
		if(!is_playing) {
			is_playing = true;

			const fullscreen_image: HTMLImageElement = document.createElement("img");
			fullscreen_image.src = `https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/assets/images/characters/${character}.png`;
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

    const audio: HTMLAudioElement = new Audio(get_site_root() + "/assets/audio/kaaris_maison-citrouille.mp3");
    let is_playing: boolean = false;

    element.addEventListener("dblclick", () => {
        if(!is_playing) {
            is_playing = true;
            audio.play().finally(() => is_playing = false);
        }
    });
}

function easter_egg_monarchy_mayhem(): void {
	const audio: HTMLAudioElement = new Audio(get_site_root() + "/assets/audio/monarchy_mayhem.mp3");
	const target_sequence: string = "monarchymayhem";
    let current_input: string = "";
	let is_playing: boolean = false;

    document.addEventListener("keydown", (event) => {
        current_input += event.key.toLowerCase();

        if(current_input.length > target_sequence.length) {
            current_input = current_input.slice(-target_sequence.length);
        }

        if(current_input === target_sequence) {			
			if(!is_playing) {
				is_playing = true;
				audio.play().finally(() => is_playing = false);
			
				const text: HTMLHeadingElement = document.createElement("h2");
				text.innerText = "Call";
				
				Object.assign(text.style, {
					position: "fixed",
					top: "10%",
					left: "35%",
					color: "white",
					fontSize: "11rem",
					textShadow: "2px 2px 4px rgba(0, 0, 0, 0.8)"
				});
				
				document.body.appendChild(text);

				setTimeout(() => {
					text.innerText = "Call 911";
				}, 250);

				setTimeout(() => {
					text.innerText = "NOW !!!!";
					text.style.rotate = "336deg";
					text.style.fontSize = "20rem"
					text.style.left = "20%";
					text.style.top = "0";
				}, 1000);

				setTimeout(() => {
					text.remove();
					const html: HTMLHtmlElement = document.querySelector("html");
					html.style.background = "#0a0523 url(" + get_site_root() + "/assets/images/easter_eggs/monarchy_mayhem_background.png) no-repeat fixed center center / cover";

					type Position = { left: string; top: string };
					const positions: Position[] = [
						{ left: "15%", top: "40%" },
						{ left: "74%", top: "46%" },
						{ left: "45%", top: "45%" },
						{ left: "22%", top: "00%" },
						{ left: "70%", top: "50%" },
						{ left: "75%", top: "-5%" },
						{ left: "45%", top: "-5%" },
						{ left: "00%", top: "70%" },
						{ left: "00%", top: "20%" }
					];

					const images: HTMLImageElement[] = [];
					positions.forEach((position, index) => {
						const img: HTMLImageElement = document.createElement("img");
						img.src = get_site_root() + `/assets/images/easter_eggs/monarchy_mayhem_${index + 1}.gif`;
						
						Object.assign(img.style, {
							position: "fixed",
							left: position.left,
							top: position.top
						});
						
						document.body.appendChild(img);
						images.push(img);
					});

					const title: HTMLHeadingElement = document.createElement("h1");
					title.innerText = "Monarchy Mayhem";
					
					Object.assign(title.style, {
						position: "fixed",
						top: "35%",
						left: "50%",
						transform: "translateX(-50%)",
						color: "white",
						fontSize: "6rem",
						fontFamily: "Arial, sans-serif",
						textShadow: "2px 2px 4px rgba(0, 0, 0, 0.8)",
						whiteSpace: "nowrap",
						animation: "colorChange 0.8s infinite alternate, rotateText 5s linear infinite"
					});
					
					document.body.appendChild(title);

					title.addEventListener("click", () => {
						window.open("https://monarchymayhem.itch.io/monarchymayhem", "_blank");
					});

					const style: HTMLStyleElement = document.createElement("style");
					style.innerHTML = `
						@keyframes colorChange {
							0% { color: red; }
							25% { color: green; }
							50% { color: blue; }
							75% { color: violet; }
							100% { color: red; }
						}
						
						@keyframes rotateText {
							0% { transform: translateX(-50%) rotate(0deg); }
							100% { transform: translateX(-50%) rotate(360deg); }
						}
					`;
					document.head.appendChild(style);

					setTimeout(() => {
						images.forEach(img => img.remove());
						title.remove();
						style.remove();
						html.style.background = "#0a0523 url(" + get_site_root() + "/assets/images/content/bg.png) no-repeat fixed center center / cover";
					}, 15500);
				}, 1600);

				current_input = "";
			}
        }
    });
}
