<?php

function get_player_farm_animals(): array {
    $data = $GLOBALS["untreated_all_players_data"];
    $animals_data = [];
    
    $all_animals = [
        "Duck"              => "Duck",
        "White Chicken"     => "Chicken",
        "Brown Chicken"     => "Chicken",
        "Blue Chicken"      => "Chicken",
        "Golden Chicken"    => "Golden Chicken",
        "Void Chicken"      => "Void Chicken",
        "Rabbit"            => "Rabbit",
        "Dinosaur"          => "Dinosaur",
        "Brown Cow"         => "Cow",
        "White Cow"         => "Cow",
        "Pig"               => "Pig",
        "Goat"              => "Goat",
        "Sheep"             => "Sheep",
        "Ostrich"           => "Ostrich"
    ];

    $animals = find_xml_tags($data, 'locations.GameLocation.buildings.Building.indoors.animals.item.value');
    
    foreach($animals as $animal) {
	    $name = (string) $animal->FarmAnimal->name;
        $full_animal_type = (string) $animal->FarmAnimal->type;
	    $friendship = (int) $animal->FarmAnimal->friendshipTowardFarmer;
	    $happiness = (int) $animal->FarmAnimal->happiness;

	    $pet = ((string) $animal->FarmAnimal->wasPet === "true");
	    $auto_pet = ((string) $animal->FarmAnimal->wasAutoPet === "true");
	    $was_pet = (($pet) || ($auto_pet));

	    $animal_data = [
	    	"name" => $name,
	    	"type" => $full_animal_type,
	    	"friendship_level" => floor($friendship / 100) / 2,
	    	"happiness" => $happiness,
	    	"was_pet" => $was_pet
	    ];

        if(!isset($all_animals[$full_animal_type])) {
            continue;
        }

        $animal_type = $all_animals[$full_animal_type];

        if(!isset($animals_data[$animal_type])) {
            $animals_data[$animal_type] = [
                "id" => get_custom_id($animal_type),
	    		"animals_data" => [],
                "counter" => 0
            ];
        }

        $animals_data[$animal_type]["counter"]++;
	    array_push($animals_data[$animal_type]["animals_data"], $animal_data);
    }

    return $animals_data;
}

function get_animal_status_tooltip(string $status, string $animal_name): string {
    return [
        "happy" => "$animal_name " . __("looks really happy today!"),
        "fine"  => "$animal_name " . __("looks fine today!"),
        "angry" => "$animal_name " . __("looks sad today :(")
    ][$status] ?? "";
}