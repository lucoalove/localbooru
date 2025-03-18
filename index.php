<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; margin: 0; display: grid; grid-template-columns: 20em 1fr; min-height: 100vh; font-size: 15px; color: #f1f1f1; background: #111014; }
		img, video { height: 200px; border: 2px solid transparent; }

		a { text-decoration: none; }

		header { padding: 1em; border: 1px solid #445; text-align: center; border-radius: 4px; }

		main { padding: 1em; }

		aside { border-right: 1px solid #445; }
		aside a { color: inherit; display: block; padding: 0.5em; transition-duration: 0.15s; }
		aside a[selected] { background: #232229; font-weight: 700; }
		aside a:hover { background: #313038; }

		svg { vertical-align: middle; transform: translateY(-2px); }

		.folder { display: inline-block; border: 2px solid #445; width: 10em; text-align: center; padding: 1em; border-radius: 4px; margin-right: 0.5em; color: inherit; }
	</style>
</head>
<body>

	<!-- php -S localhost:4444 -t . -->
	<!--
	layout inspired by https://webdesignerdepot-wp.s3.us-east-2.amazonaws.com/2023/11/27135129/01-current-DA-homepage.jpg
	and https://9gag.com/

	if you need an accent, use #59c
	-->

	<?php
		function getMediaFileCount($path) {

			if (is_dir($path)) {

				$item_paths = glob("$path/*");
				$count = 0;

				foreach ($item_paths as $path) {

					$count += getMediaFileCount($path);
				}

				return $count;

			} else {
				return 1;
			}
		}

		function insertMediaAnchor($path) {

			$uri = substr($path, 7);
			$extension = pathinfo($path)["extension"];

			if ($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "webp" || $extension == "gif") {

				echo "<a href='/$uri'><img src='/$path'></a>";
			
			} else if ($extension == "mp4" || $extension == "webm") {

				echo "<a href='/$uri'><video height='200' src='/$path'></video></a>";
			}
		}

		$path = "./home" . $_SERVER["REQUEST_URI"];

		if (substr($path, -1) == "/")
			$path = substr($path, 0, strlen($path) - 1);

		$path_is_dir = is_dir($path);
	?>

	<aside>
		<div style="position: sticky; top: 0; padding: 1em;">
			<header>
				<div style="font-size: 1.5em; font-weight: 700;">localbooru</div>
				<br>
				<strong><?php echo getMediaFileCount("./home"); ?></strong> total media files.
			</header>

			<br>

			<div>
				<a href="/" <?php if ($path == "./home/") echo "selected"; ?>>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f1f1f1"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
					Home
				</a>
				<!-- <a href="<?php

					// $potential_item_paths = [];
					
					// foreach ($board_paths as $path) {
					// 	$potential_item_paths = array_merge($potential_item_paths, glob($path . "/*"));
					// }
					
					// echo "/" . substr($potential_item_paths[array_rand($potential_item_paths)], 9);
				?>">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f1f1f1"><path d="M640-260q25 0 42.5-17.5T700-320q0-25-17.5-42.5T640-380q-25 0-42.5 17.5T580-320q0 25 17.5 42.5T640-260ZM480-420q25 0 42.5-17.5T540-480q0-25-17.5-42.5T480-540q-25 0-42.5 17.5T420-480q0 25 17.5 42.5T480-420ZM320-580q25 0 42.5-17.5T380-640q0-25-17.5-42.5T320-700q-25 0-42.5 17.5T260-640q0 25 17.5 42.5T320-580ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
					Random
				</a>
				<a href="/">
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f1f1f1"><path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>
					Upload
				</a> -->
			</div>
		</div>
	</aside>

	<main>
		<?php

			if ($path_is_dir) {

				// all items in a board

				echo "<h1>$path</h1>";

				$item_paths = glob("$path/*");

				foreach ($item_paths as $item_path) {

					$uri = substr($item_path, 7);

					if (is_dir($item_path)) {

						$name = explode("/", $item_path);
						$name = $name[array_key_last($name)];

						echo "<a class='folder' href='$uri'>" . $name . "</a>";
					}
				}
				
				echo "<br><br>";

				echo "<p style='color: #889;'>showing " . count(glob("$path/*.*")) . " media files</p>";
				// showing 1-50 (377 total)

				foreach ($item_paths as $item_path) {

					if (!is_dir($item_path)) {

						insertMediaAnchor($item_path);
					}
				}

			} else {

				// specific item

				$extension = pathinfo($path)["extension"];

				$name = explode("/", $path);
				$name = $name[array_key_last($name)];
				$name = substr($name, 0, strpos($name, "."));

				echo "<h1>" . ucwords(str_replace(["_", "%20"], " ", $name)) . " <span style='color: #889;'>$path</span></h1>";

				if ($extension == "png" || $extension == "jpg" || $extension == "jpeg" || $extension == "webp" || $extension == "gif") {

					echo "<div style='background: #050507; display: flex; justify-content: center;'><img style='height: auto; max-height: 90vh; max-width: 100%;' src='/$path'></div>";
				}

				if ($extension == "mp4" || $extension == "webm") {

					echo "<video style='background: #050507; width: 100%; height: 80vh;' controls src='/$path'></video>";
				}

				$index = explode("_", $name);
				$index = $index[array_key_last($index)];

				if (is_numeric($index)) {

					$path_wo_trailing = substr($path, 0, strlen($path) - strlen($index) - 1 - strlen($extension));
					
					$i = 1;

					while (count($siblings = glob($path_wo_trailing . $i . ".*")) != 0) {
						insertMediaAnchor($siblings[0]);
						$i++;
					}
				}
			}
		?>
	</main>

</body>
</html>
