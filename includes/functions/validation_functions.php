<?php 

/**
 * Vérifie si toutes les clés spécifiées existent dans un tableau.
 * 
 * @param array $keys Les clés à vérifier.
 * @param array $array Le tableau à vérifier.
 * @return bool Indique si toutes les clés existent.
 */
function array_keys_exists(array $keys, array $array): bool {
    return count(array_diff_key(array_flip($keys), $array)) === 0;
}

/**
 * Vérifie si un objet est vide.
 * 
 * @param object $object L'objet à vérifier.
 * @return bool Indique si l'objet est vide.
 */
function is_object_empty(object $object): bool {
	return ($object->attributes()->count() === 0);
}
