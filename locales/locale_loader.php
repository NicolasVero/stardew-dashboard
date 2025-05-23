<?php

/**
 * Charge les fichiers de traduction et met à jour les variables globales.
 *
 * @return bool Indique si le chargement des fichiers de traduction a réussi.
 */
function locale_file_loader(): bool {
    $site_language = $GLOBALS["site_language"];
    
    if (!is_a_supported_language($site_language) || is_the_original_language($site_language)) {
        $GLOBALS["is_site_translated"] = false;
        return false;
    }

    $GLOBALS["site_translations"] = [];
    $traductions_files = get_traductions_files();

    foreach ($traductions_files as $traduction_file) {
        $file_content = decode($traduction_file, get_languages_folder() . "/$site_language/");
        $GLOBALS["site_translations"] = array_merge($GLOBALS["site_translations"], expand_dynamic_keys($file_content));
    }

    $GLOBALS["wiki_link_overload"] = decode("wiki_links_overload", get_languages_folder() . "/$site_language/");
    $GLOBALS["is_site_translated"] = true;

    return true;
}

/**
 * Remplace les clés dynamiques dans les traductions.
 *
 * @param array $translations Les traductions à traiter.
 * @return array Les traductions avec les clés dynamiques remplacées.
 */
function expand_dynamic_keys(array $translations): array {
    $expanded_translations = [];

    foreach ($translations as $key => $value) {
        if (!preg_match('/\|(\d+)-(\d+)\|/', $key, $matches)) {
            $expanded_translations[$key] = $value;
            continue;
        }

        $start = (int) $matches[1];
        $end = (int) $matches[2];

        for ($i = $start; $i <= $end; $i++) {
            $expanded_key = str_replace("|$matches[1]-$matches[2]|", $i, $key);
            $expanded_value = str_replace("|$matches[1]-$matches[2]|", $i, $value);
            $expanded_translations[$expanded_key] = $expanded_value;
        }
    }

    return $expanded_translations;
}

/**
 * Retourne les fichiers de traduction à charger.
 *
 * @return array Les fichiers de traduction à charger.
 */
function get_traductions_files(): array {
    return [
        "achievement", "artifacts", "books", "characters", "cooking_recipes", "crafting_recipes",
        "enemies", "errors", "farm_animals", "fish", "full_header", "galleries", "general_stats",
        "generic", "landing_page", "minerals", "panels", "quests", "shipped_items", "topbar_panels",
        "unlockables"
    ];
}
