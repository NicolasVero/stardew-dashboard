<?php 

function locale_file_loader(): bool {

    $env = getenv();
    $site_language = $env["SITE_LANGUAGE"];

    if(!is_a_supported_language($site_language)) {
        $GLOBALS["is_site_translate"] = false;
        return false;
    }

    $GLOBALS["site_language"] = [];
    $traductions_files = get_traductions_files();

    foreach($traductions_files as $traduction_file) {
        $file_content = decode($traduction_file, get_languages_folder() . "/$site_language/");
        $GLOBALS["site_language"] = array_merge($GLOBALS["site_language"], $file_content);
    }
    log_($GLOBALS["site_language"]);
    $GLOBALS["is_site_translate"] = true;

    return true;
}

function get_traductions_files(): array {
    return [
        "landing_page", "topbar_panels", "errors"
    ];
}

function is_a_supported_language(string $language): bool {
    return in_array($language, [
        "fr"
    ]);
}
