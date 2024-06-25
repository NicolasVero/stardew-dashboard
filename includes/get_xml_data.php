<?php

require 'functions/data_functions.php';
require 'functions/display_functions.php';
require 'functions/utility_functions.php';
require 'security_check.php';


$response = array();
$response['file_content'] = $_FILES;

try {

	$name_check = explode("_", $_FILES['save-upload']['name']);	
    $external_error = ($name_check[0] == "Error") ? explode(".", ($name_check[1]))[0] : null;

    if(is_file_secure($_FILES['save-upload'], $external_error)) {
        $uploadedFile = $_FILES['save-upload']['tmp_name'];
        $data = simplexml_load_file($uploadedFile);

        load_all_items();
        load_wiki_links();
        $GLOBALS['untreated_all_players_data'] = $data;

        $players_data = get_all_players_datas();
        $players = get_all_players();

        $pages['sur_header'] = display_sur_header();
        for($player_count = 0; $player_count < count($players); $player_count++) {
			$GLOBALS['player_id'] = $player_count;
            $pages['player_' . $player_count] = "
                <div class='player_container player_{$player_count}_container'>" . 
                    display_page() . "
                </div>
            ";
        }

		//! Trop d'informations envoyées
        // $response['global_variables'] = $GLOBALS;

        $response['players'] = $GLOBALS['players_names'];
        $response['html'] = $pages;
        $response['code'] = "success";
    }
} catch (Exception $exception) {
	$page['sur_header'] = display_sur_header(true);
	$page['error_message'] = display_error_page($exception);
    $response['html']  = $page;
    $response['code']  = "failed";
    $response['error'] = $exception->getMessage();
}

echo json_encode($response);