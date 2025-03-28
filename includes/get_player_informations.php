<?php

/**
 * Retourne l'ID du joueur actuel.
 *
 * @return int L'ID du joueur actuel.
 */
function get_current_player_id(): int {
    return $GLOBALS["player_id"];
}

/**
 * Récupère les données associées à une catégorie spécifique pour un joueur donné (ou le joueur actuel par défaut).
 *
 * @param string $data_key Le chemin du fichier à inclure.
 * @param ?string $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Les données associées à la catégorie spécifiée.
 */
function get_data(string $data_key, ?int $player_id = null): array {
    $player_id = $player_id ?? get_current_player_id();
    return $GLOBALS["all_players_data"][$player_id][$data_key] ?? [];
}

/**
 * Récupère la date du joueur actuel.
 *
 * @return array La date du joueur actuel.
 */
function get_date_data(): array {
    return get_general_data()["date"];
}

/**
 * Récupère les données d'amitié d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Les données d'amitié du joueur.
 */
function get_friendships_data(?int $player_id = null): array {
    return get_data("friendship", $player_id);
}

/**
 * Récupère la liste des éléments déblocables d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des éléments déblocables du joueur.
 */
function get_unlockables_data(?int $player_id = null): array {
    return get_data("unlockables", $player_id);
}

/**
 * Récupère la liste des livres possédés d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des livres possédés du joueur.
 */
function get_books_data(?int $player_id = null): array {
    return get_data("books", $player_id);
}

/**
 * Récupère la liste des poissons attrapés d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des poissons attrapés du joueur.
 */
function get_fish_data(?int $player_id = null): array {
    return get_data("fish_caught", $player_id);
}

/**
 * Récupère la liste des minéraux trouvés d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des minéraux trouvés du joueur.
 */
function get_minerals_data(?int $player_id = null): array {
    return get_data("minerals_found", $player_id);
}

/**
 * Récupère la liste des artéfacts trouvés d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des artéfacts trouvés du joueur.
 */
function get_artifacts_data(?int $player_id = null): array {
    return get_data("artifacts_found", $player_id);
}

/**
 * Récupère la liste des ennemis tués d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des ennemis tués du joueur.
 */
function get_enemies_killed_data(?int $player_id = null): array {
    return get_data("enemies_killed", $player_id);
}

/**
 * Récupère la liste des succès débloqués d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des succès débloqués du joueur.
 */
function get_achievements_data(?int $player_id = null): array {
    return get_data("achievements", $player_id);
}

/**
 * Récupère la liste des objets expédiés d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des objets expédiés du joueur.
 */
function get_shipped_items_data(?int $player_id = null): array {
    return get_data("shipped_items", $player_id);
}

/**
 * Récupère la liste des recettes d'artisanat débloquées d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des recettes d'artisanat débloquées du joueur.
 */
function get_crafting_recipes_data(?int $player_id = null): array {
    return get_data("crafting_recipes", $player_id);
}

/**
 * Récupère la liste des animaux de la ferme des joueurs.
 *
 * @return array La liste des animaux de la ferme des joueurs.
 */

function get_farm_animals_data(): array {
    return $GLOBALS["shared_players_data"]["farm_animals"];
}

/**
 * Récupère la liste des notes secrètes trouvées d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des notes secrètes trouvées du joueur.
 */
function get_secret_notes_data(?int $player_id = null): array {
    return get_data("secret_notes", $player_id);
}

/**
 * Récupère la liste des lieux visités par le joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des lieux visités par le joueur.
 */
function get_locations_visited_data(?int $player_id = null): array {
    return get_data("locations_visited", $player_id);
}

/**
 * Récupère la liste des recettes de cuisines débloquées d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des recettes de cuisines débloquées du joueur.
 */
function get_cooking_recipes_data(?int $player_id = null): array {
    return get_data("cooking_recipes", $player_id);
}

/**
 * Récupère le journal des quêtes d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Le journal des quêtes du joueur.
 */
function get_quest_log_data(?int $player_id = null): array {
    return get_data("quest_log", $player_id);
}

/**
 * Récupère les informations générales d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Les informations générales du joueur.
 */
function get_general_data(?int $player_id = null): array {
    return get_data("general", $player_id);
}

/**
 * Récupère les compétences choisies d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Les compétences choisies du joueur.
 */
function get_skills_data(?int $player_id = null): array {
    return get_data("skills", $player_id);
}

/**
 * Récupère les niveaux de compétences d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array Les niveaux de compétences du joueur.
 */
function get_levels_data(?int $player_id = null): array {
    return get_data("levels", $player_id);
}

/**
 * Récupère la liste des maîtrises obtenues d'un joueur.
 *
 * @param ?int $player_id L'ID du joueur dont on veut récupérer les données.
 * @return array La liste des maîtrises obtenues du joueur.
 */
function get_masteries_data(?int $player_id = null): array {
    return get_data("masteries", $player_id);
}

/**
 * Récupère la liste des outils d'un joueur.
 *
 * @return array La liste des outils du joueur.
 */
function get_tools_data(): array {
    return get_general_data()["tools"];
}

/**
 * Récupère la liste des informations de la ferme.
 *
 * @return array La liste des informations de la ferme.
 */

function get_farm_informations_data(): array {
    return $GLOBALS["shared_players_data"]["farm_informations"];
}
