<?php

/**
 * Génère la structure HTML pour les panneaux de données.
 * 
 * @return string La structure HTML des panneaux de données.
 */
function display_panels(): string {
	$structure  = display_friendships();
	$structure .= display_quest_panel();
    $structure .= display_visited_locations_panel();
	$structure .= display_monster_eradication_goals_panel();
	$structure .= display_calendar_panel();
	$structure .= display_farm_animals_panel();
	$structure .= display_junimo_kart_panel();
	$structure .= display_museum_panel();
	$structure .= display_community_center_panel();
    
    return $structure;
}