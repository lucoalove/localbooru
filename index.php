<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; text-align: center; }
		img, video { height: 200px; border: 2px solid transparent; }
	</style>
</head>
<body>

	<!-- php -S localhost:4444 -t . -->

	<h1>[<a href="/">localbooru</a>]</h1>

	<div>
		<?php

			function addBoardItems($board) {
				
				$files = scandir("./boards/$board");

				foreach ($files as $file) {

					if (!is_dir("./boards/$board/$file")) {

						$uri = $board . "&" . str_replace(".", "&", $file);
						$file_extension = pathinfo($file)["extension"];

						if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

							echo "<a href='$uri'><img src='./boards/" . $board . "/" . $file . "'></a>";
						
						} else if ($file_extension == "mp4") {

							echo "<a href='$uri'><video height='200' src='./boards$board/$file'></video></a>";
						}
					}
				}
			}

			$uri_elements = explode("&", $_SERVER["REQUEST_URI"]);

			if ($uri_elements[0] == "/") {

				// all boards

				$files = scandir("./boards");

				foreach ($files as $file) {

					if ($file != "." && $file != ".." && is_dir("./boards/$file")) {

						echo "<h2><a href='$file'>/$file</a></h2>";
					}
				}

			} else if (count($uri_elements) == 1) {

				// all items in a board

				echo "<h2><a href='$uri_elements[0]'>$uri_elements[0]</a></h2>";

				addBoardItems($uri_elements[0]);

			} else {

				// specific item

				echo "<h2><a href='$uri_elements[0]'>$uri_elements[0]</a></h2>";
				echo "<h3>\"$uri_elements[1]\"</h3>";

				$file = "./boards$uri_elements[0]/$uri_elements[1].$uri_elements[2]";
				$file_extension = $uri_elements[2];

				if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

					echo "<img style='height: auto; max-height: 90vh; max-width: 100vw;' src='$file'>";
				}

				if ($file_extension == "mp4") {

					echo "<video style='width: 100%; height: 80vh;' controls src='$file'></video>";
				}
			}
		?>
	</div>

</body>
</html>
