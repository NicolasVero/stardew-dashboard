function initialize_player_swapper(players_count: number): void {
	const players_selection: HTMLCollectionOf<HTMLElement> = document.getElementsByClassName("player_selection") as HTMLCollectionOf<HTMLElement>;

	for (let i: number = 0; i < players_selection.length; i++) {
		players_selection[i].addEventListener("click", () => {
			swap_displayed_player(i % players_count);
		});
	}
}

function swap_displayed_player(player_id: number): void {
	const players_display: HTMLCollectionOf<HTMLElement> = document.getElementsByClassName("player_container") as HTMLCollectionOf<HTMLElement>;

	if (!players_display[player_id].hasAttribute('data-tooltips-initialized')) {
		initialize_tooltips(players_display[player_id].classList[0]);
		players_display[player_id].setAttribute('data-tooltips-initialized', 'true');
	}
	
	players_display[player_id].style.display = "block";

	for (let i = 0; i < players_display.length; i++) {
		if (player_id !== i) {
			players_display[i].style.display = "none";
		}
	}
}
