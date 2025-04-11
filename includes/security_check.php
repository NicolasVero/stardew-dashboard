<?php

/**
 * Vérifie si le fichier téléchargé est sécurisé.
 *
 * @param mixed $file Le fichier téléchargé.
 * @param string $external_error L'erreur externe communiquée manuellement.
 * @return bool Indique si le fichier est sécurisé ou non.
 */
function is_file_secure(mixed $file, ?string $external_error = null): bool {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);

    if ($external_error !== null) {
		switch ($external_error) {
			case "SizeException" :
				throw new Exception("Invalid file size.");
		}
	}

    if ($file["size"] < 0 || $file["size"] > in_bytes_conversion("35Mo")) {
        throw new Exception("Invalid file size.");
    }

    if ((!in_array($file["type"], ["application/xml", "text/xml", "application/octet-stream"])) || (finfo_file($finfo, $file["tmp_name"]) !== "text/xml")) {
        throw new Exception("The file is not in xml format.");
    }
    
    if (!array_keys_exists(["player", "uniqueIDForThisGame"], (array) simplexml_load_file($file["tmp_name"]))) {
        throw new Exception("File not conforming to a Stardew Valley save.");
    }
    
    if (!array_keys_exists(["gameVersion"], (array) simplexml_load_file($file["tmp_name"]))) {
        throw new Exception("Save file is from an unsupported version.");
    }

    if (!is_uploaded_file($file["tmp_name"])) {
        throw new Exception("Error loading file.");
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Error downloading file.");
    }

    return true;
}
