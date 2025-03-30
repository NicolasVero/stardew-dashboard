<?php 

/**
 * Récupère l'URL correcte à utiliser en fonction de la requête actuelle.
 *
 * @return string L'URL correcte.
 */
function get_correct_url(): string {
	return (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : $_SERVER["REQUEST_URI"];
}

/**
 * Récupère l'URL racine du site en fonction de l'environnement.
 *
 * @return string La racine du site.
 */
function get_site_root(): string {
	if (is_on_localhost()) {
		return "http://localhost/travail/stardew_dashboard";
	}
	
	$protocol = (empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "off") ? "http" : "https";
	return "$protocol://stardew-dashboard.42web.io";
}

/**
 * Récupère le répertoire racine du site en fonction de l'environnement.
 * 
 * @return string Le répertoire racine du site.
 */
function get_site_directory(): string {
	return strstr(__DIR__, 'stardew_dashboard', true) . 'stardew_dashboard';
}

/**
 * Récupère le répertoire des fichiers JSON.
 * 
 * @return string Le répertoire des fichiers JSON.
 */
function get_json_folder(): string {
    return get_site_root() . "/data/json";
}

/**
 * Récupère le répertoire des langues.
 * 
 * @return string Le répertoire des langues.
 */
function get_languages_folder(): string {
    return get_site_root() . "/locales/languages";
}

/**
 * Récupère le répertoire des sauvegardes.
 * 
 * @param bool $use_directory Indique si le répertoire ou l'URL absolue doit être retourné.
 * @return string Le répertoire des sauvegardes.
 */
function get_saves_folder(bool $use_directory = false): string {
    if ($use_directory) {
		return get_site_directory() . "/data/saves";
	}

	return get_site_root() . "/data/saves";
}

/**
 * Vérifie si une sauvegarde existe.
 * 
 * @param string $save Le nom de la sauvegarde.
 * @return bool Indique si la sauvegarde existe.
 */
function does_save_exists(string $save): bool {
    return is_file(get_saves_folder(true) . "/$save");
}

/**
 * Récupère le répertoire des images.
 * 
 * @param bool $is_external Indique si l'URL doit être externe ou local.
 * @return string Le répertoire des images.
 */
function get_images_folder(bool $is_external = false): string {
	return ($is_external || !is_on_localhost()) ? get_github_assets_url() : get_site_root() . "/assets/images";
}

/**
 * Récupère l'URL des assets sur GitHub.
 * 
 * @return string L'URL des assets sur GitHub.
 */
function get_github_assets_url(): string {
	return "https://raw.githubusercontent.com/NicolasVero/stardew-dashboard/refs/heads/master/assets/images";
}

/**
 * Vérifie si le site est en localhost ou non.
 * 
 * @return bool Indique si le site est en localhost.
 */
function is_on_localhost(): bool {
	return $_SERVER["HTTP_HOST"] === "localhost";
}

/**
 * Récupère la taille maximale de téléchargement autorisée en PHP.
 * 
 * @return string La taille maximale de téléchargement autorisée.
 */
function get_php_max_upload_size(): string {
	$post_max_size_bytes = in_bytes_conversion(ini_get("post_max_size"), "server");
	return json_encode([
        "post_max_size" => $post_max_size_bytes
    ]);
}

/**
 * Vérifie si l'utilisateur est sur un appareil mobile.
 * 
 * @return bool Indique si l'utilisateur est sur un appareil mobile.
 */
function is_a_mobile_device(): bool {
	return (
		stristr($_SERVER["HTTP_USER_AGENT"], "Android") ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPod") !== false ||
		strpos($_SERVER["HTTP_USER_AGENT"], "iPhone") !== false 
	);
}
