<?php 

/**
 * Recherche des balises XML correspondant à un chemin spécifié dans un objet XML.
 * 
 * @param object $xml_object L'objet XML.
 * @param string $tag_path Le chemin de la balise XML à rechercher.
 * @return array Les balises XML trouvées.
 */
function find_xml_tags(object $xml_object, string $tag_path): array {
    $path_elements = explode('.', $tag_path);
    return recursive_xml_search($xml_object, $path_elements);
}

/**
 * Recherche récursive de balises XML en suivant un chemin donné.
 * 
 * @param object $current_level Le niveau actuel de l'objet XML.
 * @param array $remaining_path Le chemin restant à suivre.
 * @return array Les balises XML trouvées.
 */
function recursive_xml_search(object $current_level, array $remaining_path): array {
    $results = [];

    if (empty($remaining_path)) {
        return is_array($current_level) ? $current_level : [$current_level];
    }

    $current_tag = $remaining_path[0];

    if (!isset($current_level->$current_tag)) {
        return $results;
    }

    if (count($remaining_path) === 1) {
        foreach ($current_level->$current_tag as $item) {
            $results[] = $item;
        }
		
        return $results;
    }

    $child_remaining_path = array_slice($remaining_path, 1);

    foreach ($current_level->$current_tag as $child) {
        $child_results = recursive_xml_search($child, $child_remaining_path);
        $results = array_merge($results, $child_results);
    }

    return $results;
}

/**
 * Récupère l'index d'une balise XML correspondant à un chemin spécifié dans un objet XML.
 * 
 * @param object $raw_data Les données générales du joueur.
 * @param string $searched_location La balise XML à rechercher.
 * @return int L'index de la balise XML trouvée.
 */
function get_gamelocation_index(object $raw_data, string $searched_location): int {
	$index = 0;
	$locations = $raw_data->locations->GameLocation;

	foreach ($locations as $location) {
		if (isset($location->$searched_location)) {
			break;
		}
		
		$index++;
	}

	return $index;
}

/**
 * Récupère l'index de la balise XML correspondant au musée.
 * 
 * @return int L'index de la balise XML GameLocation du musée.
 */
function get_museum_index(): int {
    $raw_data = $GLOBALS["raw_xml_data"];
	return get_gamelocation_index($raw_data, "museumPieces");
}
