<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; margin: 0; display: grid; grid-template-columns: 20em 1fr; min-height: 100vh; font-size: 15px; color: #f1f1f1; background: #111014; }
		img, video { height: 200px; border: 2px solid transparent; }

		a { text-decoration: none; }

		header { padding: 1em; border: 1px solid #445; text-align: center; border-radius: 4px; font-size: 1.5em; font-weight: 700; }

		main { padding: 1em; }

		aside { border-right: 1px solid #445; }
		aside a { color: inherit; display: block; padding: 0.5em; transition-duration: 0.15s; }
		aside a[selected] { background: #232229; font-weight: 700; }
		aside a:hover { background: #313038; }

		svg { vertical-align: middle; transform: translateY(-2px); }
	</style>
</head>
<body>

	<!-- php -S localhost:4444 -t . -->
	<!--
	layout inspired by https://webdesignerdepot-wp.s3.us-east-2.amazonaws.com/2023/11/27135129/01-current-DA-homepage.jpg
	and https://9gag.com/
	-->

	<?php
		$uri_elements = explode("/", $_SERVER["REQUEST_URI"]);
		$board_paths = glob("./boards/*");

		$item_files_count = 0;
	?>

	<aside>
		<div style="position: sticky; top: 0; padding: 1em;">
			<header>localbooru</header>

			<a href="/" style="text-align: center; background: #59c; color: black; font-weight: 700; margin: 1em 0; border-radius: 4px;">Upload</a>

			<div style="font-weight: 700;">
				<a href="/" <?php if ($uri_elements[1] == "") echo "selected"; ?>>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f1f1f1"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
					Home
				</a>
				<a href="<?php

					$potential_item_paths = [];
					
					foreach ($board_paths as $path) {
						$potential_item_paths = array_merge($potential_item_paths, glob($path . "/*"));
					}
					
					echo "/" . substr($potential_item_paths[array_rand($potential_item_paths)], 9);
				?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f1f1f1"><path d="M640-260q25 0 42.5-17.5T700-320q0-25-17.5-42.5T640-380q-25 0-42.5 17.5T580-320q0 25 17.5 42.5T640-260ZM480-420q25 0 42.5-17.5T540-480q0-25-17.5-42.5T480-540q-25 0-42.5 17.5T420-480q0 25 17.5 42.5T480-420ZM320-580q25 0 42.5-17.5T380-640q0-25-17.5-42.5T320-700q-25 0-42.5 17.5T260-640q0 25 17.5 42.5T320-580ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
					Random
				</a>
			</div>
			<br>

			<h2 style="color: #888; font-size: 1em;">Boards</h2>
			<?php
				foreach ($board_paths as $path) {

					if (is_dir($path)) {

						$name = substr($path, 9);

						if ($uri_elements[1] == $name)
							echo "<a href='/$name' selected>$name</a>";
						else
							echo "<a href='/$name'>$name</a>";

						$item_files_count += count(glob("$path/*"));
					}
				}
			?>
		</div>
	</aside>

	<main>
		<?php

			function addBoardItems($board_name) {
				
				$item_paths = glob("./boards/$board_name/*");

				foreach ($item_paths as $path) {

					if (!is_dir($path)) { // there shouldn't be directories but you never know

						$uri = substr($path, 9);
						$extension = pathinfo($path)["extension"];

						if ($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "webp" || $extension == "gif") {

							echo "<a href='$uri'><img src='$path'></a>";
						
						} else if ($extension == "mp4" || $extension == "webm") {

							echo "<a href='$uri'><video height='200' src='$path'></video></a>";
						}
					}
				}
			}

			if ($uri_elements[1] == "") {

				// home

				echo "<h1>Welcome to <span style='color: #59c;'>localbooru!</span></h1><p>We are currently hosting <strong>$item_files_count</strong> images.</p>";

			} else if (count($uri_elements) == 2) {

				// all items in a board

				echo "<h1>$uri_elements[1]</h1>";

				addBoardItems($uri_elements[1]);

			} else {

				// specific item

				$file = "/boards/$uri_elements[1]/$uri_elements[2]";
				$extension = pathinfo($uri_elements[2])["extension"];

				if ($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "webp" || $extension == "gif") {

					echo "<div style='background: #050507; display: flex; justify-content: center;'><img style='height: auto; max-height: 90vh; max-width: 100%;' src='$file'></div>";
				}

				if ($extension == "mp4" || $extension == "webm") {

					echo "<video style='background: #050507; width: 100%; height: 80vh;' controls src='$file'></video>";
				}

				echo "<h1>" . ucwords(str_replace("_", " ", substr($uri_elements[2], 0, strpos($uri_elements[2], ".")))) . "</h1>";
				echo "<p style='color: #445;'>$uri_elements[1]/$uri_elements[2]</p>";
			}
		?>
	</main>

</body>
</html>