<?php

require_once "includes/utility_functions.php";
require_once "includes/functions_loader.php";
require_once "includes/get_player_informations.php";
require_once "includes/display_pages.php";

require_once "includes/extract_data_from_save.php";

$lang = $_GET["lang"] ?? "default";
putenv("SITE_LANGUAGE=$lang");
require_once "locales/locale_loader.php";
locale_file_loader();


if(isset($_GET["action"]) && $_GET["action"] === "get_max_upload_size") {	
	echo get_php_max_upload_size();
}

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "display_feedback_panel") {
    echo display_feedback_panel();
}