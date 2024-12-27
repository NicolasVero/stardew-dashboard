<?php

$lang = "es_ES";
// $lang = "fr_FR";
$domain = [
    "en_US" => "default",
    "es_ES" => "app_es",
    "fr_FR" => "app_fr"
][$lang];

bindtextdomain($domain, __DIR__ . "/locale");
textdomain($domain);

putenv('LANGUAGE=' . $lang);
putenv('LANG=' . $lang);

setlocale(LC_ALL, $lang);

require_once "functions.php";

require_once "components/header.php";

require_once "includes/page_loader.php";

require_once "components/footer.php";