function initialize_player_swapper(players_count: number): void {
	const players_selection: HTMLCollectionOf<HTMLElement> = document.getElementsByClassName("player_selection") as HTMLCollectionOf<HTMLElement>;

	for(let i = 0; i < players_selection.length; i++) {
		players_selection[i].addEventListener("click", () => {
			swap_displayed_player(i % players_count);
		});
	}
}

function swap_displayed_player(player_id: number): void {
	const players_display: HTMLCollectionOf<HTMLElement> = document.getElementsByClassName("player_container") as HTMLCollectionOf<HTMLElement>;

	for(let i = 0; i < players_display.length; i++) {
		players_display[i].style.display = (player_id !== i) ? "none" : "block";
		players_display[i].style.opacity = "1";
	}
}