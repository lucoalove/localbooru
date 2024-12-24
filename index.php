<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; }
		img, video { height: 200px; border: 2px solid transparent; }

		h1 { text-align: center; font-size: 4em; }
		h2 { text-align: center; font-size: 2em; }
	</style>
</head>
<body>

	<h1>[<a href="/">localbooru</a>]</h1>

	<div>
		<?php

			function addBoard($board) {

				echo "<h2>$board</h2>";
				
				$files = scandir("./boards/$board");

				foreach ($files as $file) {

					if (!is_dir("./boards/$board/$file")) {

						$file_extension = pathinfo($file)["extension"];

						if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

							$uri = $board . "&" . str_replace(".", "&", $file);

							echo "<a href='$uri'><img src='./boards/" . $board . "/" . $file . "'></a>";
						
						} else if ($file_extension == "mp4") {

							echo "<video height='200' controls src='./boards/" . $board . "/" . $file . "'></video>";
						}
					}
				}
			}

			$uri_elements = explode("&", $_SERVER["REQUEST_URI"]);

			if (count($uri_elements) == 1) {

				// home (all boards/items)

				$files = scandir("./boards");

				foreach ($files as $file) {

					if ($file != "." && $file != ".." && is_dir("./boards/$file")) {

						addBoard($file);
					}
				}

			} else {

				// specific item

				echo "<h2>" . $uri_elements[0] . " => " . $uri_elements[1] . "</h2>";

				$file = "./boards$uri_elements[0]/$uri_elements[1].$uri_elements[2]";
				$file_extension = $uri_elements[2];

				if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

					echo "<img style='height: auto; max-height: 90vh; max-width: 100vw;' src='$file'>";
				}

				if ($file_extension == "mp4") {

					echo "<video height='600' controls src='$file'></video>";
				}
			}
		?>
	</div>

</body>
</html>
