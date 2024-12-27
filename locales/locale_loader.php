<?php 

function locale_file_loader(): bool {

    $env = getenv();
    $site_language = $env["SITE_LANGUAGE"];
    $json_file = $site_language . "_language";

    if(!is_a_supported_language($site_language)) {
        $GLOBALS["is_site_translate"] = false;
        return false;
    }

    $GLOBALS["site_language"] = decode($json_file, get_languages_folder());
    $GLOBALS["is_site_translate"] = true;

    return true;
}

function is_a_supported_language(string $language): bool {
    return in_array($language, [
        "fr"
    ]);
}

function __(string $text): string {
    if(!$GLOBALS["is_site_translate"]) {
        return $text;
    }

    return $GLOBALS["site_language"][$text] ?? $text;
}