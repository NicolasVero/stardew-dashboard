<?php 

function display_landing_page(bool $with_topbar = true): string {
	if(is_a_mobile_device()) {
		return display_mobile_landing_page();
	}

    $images_path = get_images_folder();
	$topbar = ($with_topbar) ? display_topbar(true, false) : "";
    $save_panel = display_save_panel();
    $settings_panel = display_settings_panel();

	$contributors_structure = "";
	$contributors = get_contributors();

	foreach($contributors as $contributor) {
		$contributors_structure .= display_project_contributor($contributor);
	}

    return "
        $topbar
        $save_panel
        $settings_panel
        <div id='display'>
			<div id='landing_page'>
				<main>
					<h1 class='section-title'>Welcome to Stardew Dashboard</h1>
					<section class='project-description'>
						<h2 class='section-title'>What is Stardew Dashboard?</h2>
						<span>
							<span>
								Are you an avid farmer in Stardew Valley looking to optimize your gameplay experience? Look no further! Stardew Dashboard is your ultimate companion to manage your farm and track your progress.
							</span>
							<span>
								Upload your game save file effortlessly and gain access to a wealth of information about your farm, from tracking your progress in mastering recipes to discovering new elements of the game world. With our intuitive interface, staying on top of your farm's needs and exploring everything that Stardew Valley has to offer has never been easier.
							</span>
							<span>
								Whether you're a seasoned veteran or just starting out, Stardew Dashboard is here to enhance your Stardew Valley experience. Join our community today and take your farming to the next level!
							</span>
							<span>
								Our tool only works on versions higher than 1.4.
							</span>
						</span>
					</section>
					<section class='how-to-use-it'>
						<h2 class='section-title'>How to use it</h2>
						<span>
							<span>
								To start using Stardew Dashboard, retrieve your save
								<code id='save_os_path'>(C:\Users\UserName\AppData\Roaming\StardewValley\Saves\SaveName).</code>
								The save file is the one with the same name as your folder.
							</span>
							<span>
								Well done! The hardest part is behind us! Now you just have to upload your save 
								<span class='img-embed landing-upload'>
									<img src='$images_path/icons/file.png' class='modal-opener' alt='File upload icon'/>
								</span>
								directly to our website and let the magic happen.
							</span>
							<span>
								There's also a range of settings
								<span class='img-embed landing-settings'>
									<img src='$images_path/icons/settings.png' class='modal-opener' alt='Settings icon'/>
								</span>
								to customize your experience!
							</span>
						</span>
					</section>
					
					<section class='feedback'>
						<h2 class='section-title'>We value your feedback</h2>
						<span>
							<span>
								Your experience with Stardew Dashboard is important to us.
								We continuously strive to improve and would love to hear your thoughts and suggestions. Whether it's a feature request, a bug report, or general feedback, your input helps us make Stardew Dashboard even better.
							</span>
							<span>
								Click
								<span class='img-embed feedback-opener'>
									<img src='$images_path/icons/feedback.png' class='modal-opener' alt='Feedback icon'/>
								</span>
								to open the feedback form and share your thoughts with us.
								Thank you for being a part of our community and helping us grow!
							</span>
						</span>
					</section>

					<section class='about'>
						<h2 class='section-title'>About us</h2>
						<span>
							<span>
								Stardew Dashboard is a project made by two French students in their third year of a bachelor's degree in web development.
								Created during our spare time, this website serves as a tool for us to conveniently track our progress in Stardew Valley. 
							</span>
						</span>
						<span class='characters'>
							$contributors_structure
						</span>
					</section>
				</main>
        	</div>
        </div>
        <img src='$images_path/content/loading.gif' id='loading-strip' class='loading' alt=''/>
    ";
}

function display_mobile_landing_page(): string {
	$contributors_structure = "";
	$contributors = get_contributors();

	foreach($contributors as $contributor) {
		$contributors_structure .= display_project_contributor($contributor);
	}
	
	return "
		<div id='display'>
			<div id='mobile_landing_page'>
				<main>
					<h1 class='section-title'>Welcome to Stardew Dashboard</h1>
					<section class='project-description'>
						<h2 class='section-title'>Oh no!</h2>
						<span>
							<span>
								Unfortunately, the tool is not available on smartphone. Go and try it on a computer !
							</span>
						</span>
					</section>
					
					<section class='about'>
						<h2 class='section-title'>About us</h2>
						<span>
							<span>
								Stardew Dashboard is a project made by two French students in their third year of a bachelor's degree in web development.
								Created during our spare time, this website serves as a tool for us to conveniently track our progress in Stardew Valley. 
							</span>
						</span>
						<span class='characters'>
							$contributors_structure
						</span>
					</section>
				</main>
        	</div>
        </div>
	";
}

function display_page(): string {
    $structure = display_header();
    $structure .= "<main>";
		$structure .= display_panels();

		$structure .= display_general_stats();
		
		$structure .= "<div class='separated-galleries first-gallery'>";
			$structure .= "<div class='intra-gallery _50'>";
				$structure .= display_skills();
				$structure .= display_farm_animals();
			$structure .= "</div>";
			$structure .= display_top_friendships();
		$structure .= "</div>";
			
			$structure .= "<div class='separated-galleries'>";
			$structure .= display_unlockables();
			$structure .= display_books();
			
			$structure .= display_cooking_recipes();
			$structure .= display_fish();
			
			$structure .= display_minerals();
			$structure .= display_artifacts();
			
			$structure .= display_achievements();
			$structure .= display_secret_notes();

			$structure .= display_enemies();

			$structure .= display_shipped_items();
			
			$structure .= display_crafting_recipes();
		$structure .= "</div>";

    $structure .= "</main>";

    return $structure;
}

function display_error_page(Exception $exception): string {
    $images_path = get_images_folder();
    $exception_dialogues = [
        "The file is not in xml format." => [
            "dialogue" => "Oh, my stars! It appears this file has lost its way in the tangled underbrush of incompatible formats. XML, my dear friend, is the language of precision and organization, much like the delicate balance of ecosystems on our beloved Ginger Island!",
            "image"    => "dialogue_box_professor_snail"
		],
        "Error loading file." => [
            "dialogue" => "Oh, bother! It seems like the file got lost in the mines. Could you try again? Or perhaps seek help from a trusty adventurer to retrieve it?",
            "image"    => "dialogue_box_dwarf"
		],
        "Error downloading file." => [
            "dialogue" => "Oops! Looks like the file is playing hide and seek in the shadows. Maybe a stealthier approach is needed to capture it. Keep your eyes peeled, friend!",
            "image"    => "dialogue_box_henchman"
		],
        "Invalid file size." => [
            "dialogue" => "Hold up there! The file size seems a bit too hefty for our cozy little village. Let's trim it down a tad before trying to squeeze it through the gate, shall we?",
            "image"    => "dialogue_box_bouncer"
		],
        "File not conforming to a Stardew Valley save." => [
            "dialogue" => "Ah, shucks! This file doesn't quite match the charm of Stardew Valley. It's like trying to plant a melon seed in winter – just won't work! Let's find a file more in tune with the rhythm of the seasons, shall we?",
            "image"    => "dialogue_box_grandpa"
		],
        "Save file is from an unsupported version." => [
            "dialogue" => "Ah, wanderer of the shadows, it seems your version is shrouded in the mists of the past, before the 1.4 era. The depths of the sewers have whispered to me of the new wonders that await in the updated realms. To venture forth, you must embrace the light of the latest version.",
            "image"    => "dialogue_box_krobus"
        ]
	];

    extract($exception_dialogues[$exception->getMessage()]);

    return "
        <div class='error-wrapper'>
            <div class='dialogue-box-error-container'>
                <img src='$images_path/dialogue_boxes/$image.png' alt='" . $exception->getMessage() . "'/>
                <span>$dialogue</span>
            </div>
        </div>
	";
}