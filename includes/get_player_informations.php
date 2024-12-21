<?php 

function get_current_player_id(): int {
    return $GLOBALS["player_id"];
}

function get_data(string $data_key, int $player_id = null): array {
    $player_id = $player_id ?? get_current_player_id();
    return $GLOBALS["all_players_data"][$player_id][$data_key] ?? [];
}

function get_friendships_data(int $player_id = null): array {
    return get_data("friendship", $player_id);
}

function get_unlockables_data(int $player_id = null): array {
    return get_data("unlockables", $player_id);
}

function get_books_data(int $player_id = null): array {
    return get_data("books", $player_id);
}

function get_fish_data(int $player_id = null): array {
    return get_data("fish_caught", $player_id);
}

function get_minerals_data(int $player_id = null): array {
    return get_data("minerals_found", $player_id);
}

function get_artifacts_data(int $player_id = null): array {
    return get_data("artifacts_found", $player_id);
}

function get_enemies_killed_data(int $player_id = null): array {
    return get_data("enemies_killed", $player_id);
}

function get_achievements_data(int $player_id = null): array {
    return get_data("achievements", $player_id);
}

function get_shipped_items_data(int $player_id = null): array {
    return get_data("shipped_items", $player_id);
}

function get_crafting_recipes_data(int $player_id = null): array {
    return get_data("crafting_recipes", $player_id);
}

function get_farm_animals_data(): array {
    return $GLOBALS["shared_players_data"]["farm_animals"];
}

function get_secret_notes_data(int $player_id = null): array {
    return get_data("secret_notes", $player_id);
}

function get_locations_visited_data(int $player_id = null): array {
    return get_data("locations_visited", $player_id);
}

function get_cooking_recipes_data(int $player_id = null): array {
    return get_data("cooking_recipes", $player_id);
}

function get_quest_log_data(int $player_id = null): array {
    return get_data("quest_log", $player_id);
}

function get_general_data(int $player_id = null): array {
    return get_data("general", $player_id);
}

function get_skills_data(int $player_id = null): array {
    return get_data("skills", $player_id);
}

function get_levels_data(int $player_id = null): array {
    return get_data("levels", $player_id);
}

function get_masteries_data(int $player_id = null): array {
    return get_data("masteries", $player_id);
}