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

    foreach($data->locations->GameLocation as $location) {
        if(!isset($location->buildings)) {
            continue;
        }

        foreach($location->buildings->Building as $building) {
            if(!isset($building->indoors->animals)) {
                continue;
            }

            foreach($building->indoors->animals->item as $animal) {
				$name = (string) $animal->value->FarmAnimal->name;
                $full_animal_type = (string) $animal->value->FarmAnimal->type;
				$friendship = (int) $animal->value->FarmAnimal->friendshipTowardFarmer;
				$happiness = (int) $animal->value->FarmAnimal->happiness;

				$pet = ((string) $animal->value->FarmAnimal->wasPet === "true") ? true : false;
				$auto_pet = ((string) $animal->value->FarmAnimal->wasAutoPet === "true") ? true : false;
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
        }

        break;
    }

    return $animals_data;
}

function get_animal_status_tooltip(string $status, string $animal_name): string {
    return [
        "happy" => "$animal_name looks really happy today!",
        "fine"  => "$animal_name looks fine today!",
        "angry" => "$animal_name looks sad today :("
    ][$status] ?? "";
}