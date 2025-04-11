<?php

/**
 * Récupère le script pour charger les éléments du tableau de bord.
 * 
 * @return string Le script pour charger les éléments du tableau de bord.
 */
function get_script_loader(): string {
	return "
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				load_dashboard_elements();
			});
		</script>
	";
}

/**
 * Renvoie la chaîne de caractères pour les galleries vides.
 * 
 * @return string La chaîne de caractères.
 */
function no_items_placeholder(): string {
	return __("Nothing to see here");
}

/**
 * Génère le texte concernant les contributeurs d'un projet.
 * 
 * @param array $options Les options pour générer le texte.
 * @return string Le texte concernant les contributeurs d'un projet.
 */
function display_project_contributor(array $options): string {
    extract($options); //? $name, $icon, $texts, $socials

    $images_path = get_images_folder();
    $portrait =  "$images_path/content/$icon.png";
    $presentation = "";
    $socials_links = "";

    foreach ($texts as $text) {
        $presentation .= "<span>$text</span>";
    }

    foreach ($socials as $social_name => $social) {
        extract($social); //? $url, $on_display
		
        if (!$on_display) {
			continue;
        }

		$socials_links .= "<a href='$url' rel='noreferrer' target='_blank'><img src='$images_path/social/$social_name.png' alt='$social_name'></a>";
    }

    return "
        <span>
            <img src='$portrait' class='character-image $icon' alt='$name'>
            <span>
                <span class='character-presentation'>
                    $presentation
                </span>
                <span class='socials'>
                    $socials_links
                </span>
            </span>
        </span>
    ";
}

/**
 * Génère le texte d'affichage de la bande de chargement.
 * 
 * @return string Le texte d'affichage de la bande de chargement.
 */
function display_loading_strip(): string {
	$images_path = get_images_folder();
	$loading_translation = __("loading");
	return "<img src='$images_path/content/strip_$loading_translation.gif' class='loading' id='loading-strip' alt=''>";
}

/**
 * Récupère les informations des contributeurs du projet.
 * 
 * @return array Les informations des contributeurs.
 */
function get_contributors(): array {
	return [
		[
			"name" => "Romain",
			"icon" => "romain",
			"texts" => [
				__("Romain is a passionate web developer currently pursuing a master's degree at USTC, China."),
				__("He played a major role in both the Front-End and Back-End development of the project.")
			],
			"socials" => [
				"github" => [
					"url" => "https://github.com/BreadyBred",
					"on_display" => true
				],
				"linkedin" => [
					"url" => "https://www.linkedin.com/in/romain-gerard/",
					"on_display" => true
				],
				"website" => [
					"url" => "https://romain-gerard.com/",
					"on_display" => true
				],
				"codewars" => [
					"url" => "https://www.codewars.com/users/BreadyBred",
					"on_display" => true
				],
				"reddit" => [
					"url" => "",
					"on_display" => false
				],
				"pinterest" => [
					"url" => "",
					"on_display" => false
				],
				"instagram" => [
					"url" => "",
					"on_display" => false
				],
				"x" => [
					"url" => "",
					"on_display" => false
				]
			]
		],
		[
			"name" => "Nicolas",
			"icon" => "nicolas",
			"texts" => [
				__("Nicolas is passionate about sleep and web development. He works as a web developer at Neoma Business School."),
				__("He took care of the Back-End of the website, as well as the UX / UI design.")
			],
			"socials" => [
				"github" => [
					"url" => "https://github.com/NicolasVero",
					"on_display" => true
				],
				"linkedin" => [
					"url" => "https://www.linkedin.com/in/nicolas-vero/",
					"on_display" => true
				],
				"website" => [
					"url" => "https://nicolas-vero.fr/",
					"on_display" => false
				],
				"codewars" => [
					"url" => "https://www.codewars.com/users/NicolasVero",
					"on_display" => true
				],
				"reddit" => [
					"url" => "",
					"on_display" => false
				],
				"pinterest" => [
					"url" => "",
					"on_display" => false
				],
				"instagram" => [
					"url" => "",
					"on_display" => false
				],
				"x" => [
					"url" => "",
					"on_display" => false
				]
			]
		]
	];
}
