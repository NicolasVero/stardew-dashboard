<?php

$domain = "app";
$lang = "fr_FR";
$lang = "es_ES";
putenv('LANG=' . $lang);
putenv("LANGUAGE=" . $lang);

bindtextdomain($domain, "./locale");
textdomain($domain);

if(!setlocale(LC_ALL, "es_ES", "es-ES")) {
    throw new Exception("Locale non supportée : " . $lang);
}



require_once "functions.php";

require_once "components/header.php";

require_once "includes/page_loader.php";

require_once "components/footer.php";