<?php 

function locale_file_loader(): bool {

    $site_language = $GLOBALS["site_language"];

    if(!is_a_supported_language($site_language)) {
        $GLOBALS["is_site_translated"] = false;
        return false;
    }

    $GLOBALS["site_translations"] = [];
    $traductions_files = get_traductions_files();

    foreach($traductions_files as $traduction_file) {
        $file_content = decode($traduction_file, get_languages_folder() . "/$site_language/");
        $GLOBALS["site_translations"] = array_merge($GLOBALS["site_translations"], $file_content);
    }
    
    $GLOBALS["is_site_translated"] = true;
    return true;
}

function get_traductions_files(): array {
    return [
        "landing_page", "topbar_panels", "errors", "dashboard"
    ];
}

function is_a_supported_language(string $language): bool {
    return in_array($language, [
        "fr"
    ]);
}
