<?php 

/**
 * Traduit un texte si une traduction est disponible et ajoute des espaces en fonction de l'option spécifiée.
 *
 * @param string $text Le texte à traduire.
 * @param int $option L'option de l'espacement du texte.
 * @return string Le texte traduit.
 */
function __(string $text, int $option = SPACE_NONE): string {
    if ($GLOBALS["is_site_translated"]) {
		if (str_contains($text, "stardewvalleywiki")) {
			return get_translated_wiki_link($text, $GLOBALS["site_language"]);
		}
		
		$text = $GLOBALS["site_translations"][$text] ?? $text;
	}

    return [
        SPACE_NONE => $text,
        SPACE_BEFORE => " $text",
        SPACE_AFTER => "$text ",
        SPACE_BOTH => " $text ",
    ][$option] ?? $text;
}

/**
 * Traduit un lien du wiki si une traduction est disponible.
 *
 * @param string $wiki_link Le lien du wiki à traduire.
 * @param string $lang La langue du site.
 * @return string Le lien du wiki traduit.
 */
function get_translated_wiki_link(string $wiki_link, string $lang): string {
	$wiki_url = parse_url($wiki_link);
	$wiki_page = basename($wiki_url["path"]);

	if (isset($GLOBALS["wiki_link_overload"][$wiki_page])) {
		return $GLOBALS["wiki_link_overload"][$wiki_page];
	}

	$wiki_base_url = [
		"fr" => "https://fr.stardewvalleywiki.com/",
		"de" => "https://de.stardewvalleywiki.com/",
		"es" => "https://es.stardewvalleywiki.com/",
		"it" => "https://it.stardewvalleywiki.com/",
		"pt" => "https://pt.stardewvalleywiki.com/",
		"tr" => "https://tr.stardewvalleywiki.com/"
	][$lang];

    if (isset($wiki_url["path"])) {
        return $wiki_base_url . __(str_replace("_", " ", $wiki_page));
    }

	return "";
}

/**
 * Vérifie si la langue actuelle est la langue originale.
 *
 * @param string $language La langue à vérifier.
 * @return bool Indique si la langue est la langue originale.
 */
function is_the_original_language(string $language): bool {
	return $language == "en";
}

/**
 * Vérifie si la langue est une langue supportée.
 *
 * @param string $language La langue à vérifier.
 * @return bool Indique si la langue est supportée.
 */
function is_a_supported_language(string $language): bool {
    return in_array($language, get_supported_languages());
}

/**
 * Récupère les langues supportées.
 *
 * @return array Les langues supportées.
 */
function get_supported_languages(): array {
	return [
		"en", "fr", "de", "es", "it", "pt", "tr"
	];
}

/**
 * Récupère la langue du site.
 *
 * @return string La langue du site.
 */
function get_site_language(): string {
	return $GLOBALS["site_language"] ?? "en";
}

/**
 * Définit la langue du site.
 *
 * @return void
 */
function define_site_language(): void {
	$url = get_correct_url();

	$url_without_query = parse_url($url, PHP_URL_PATH);
	$url_trimmed = rtrim($url_without_query, '/');
	$lang = basename($url_trimmed);
	$GLOBALS["site_language"] = (is_a_supported_language($lang)) ? $lang : "en";
}
