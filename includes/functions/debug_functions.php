<?php 

/**
 * Affiche un élément dans une balise <pre> pour le débogage.
 *
 * @param mixed $element L'élément à afficher.
 * @param string $title Le titre de l'élément.
 * @return void
 */
function log_(mixed $element, ?string $title = null): void {
    if ($title !== null) {
		echo "<h2>$title</h2>";
	}
    
	echo "<pre>" . print_r($element, true) . "</pre>";
}
