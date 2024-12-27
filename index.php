<?php

require_once "functions.php";
putenv("SITE_LANGUAGE=fr");
require_once "locales/locale_loader.php";
locale_file_loader();

require_once "components/header.php";

require_once "includes/page_loader.php";

require_once "components/footer.php";