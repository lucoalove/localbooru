<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; margin: 0; display: grid; grid-template-columns: 20em 1fr; grid-template-rows: auto 1fr; min-height: 100vh; }
		img, video { height: 200px; border: 2px solid transparent; }

		h1 { margin: 0; }

		header, aside, main { padding: 1em; }

		header { background: #159; color: white; grid-column: 1 / span 2; text-align: center; }
		main { background: #cef; }
		aside { background: #fff; border-right: 1px solid black; }
	</style>
</head>
<body>

	<!-- php -S localhost:4444 -t . -->
	<!-- layout inspired by https://webdesignerdepot-wp.s3.us-east-2.amazonaws.com/2023/11/27135129/01-current-DA-homepage.jpg -->

	<header>
		<h1>[<a href="/">localbooru</a>]</h1>
	</header>

	<aside>
		<strong>BOARDS</strong>
		<?php
			$files = scandir("./boards");

			foreach ($files as $file) {

				if ($file != "." && $file != ".." && is_dir("./boards/$file")) {

					echo "<br><br><a href='$file'>/$file</a>";
				}
			}
		?>
	</aside>

	<main>
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

				// home

			} else if (count($uri_elements) == 1) {

				// all items in a board

				echo "<h2>$uri_elements[0]</h2>";

				addBoardItems($uri_elements[0]);

			} else {

				// specific item

				echo "<h2>$uri_elements[0]/$uri_elements[1]</h2>";

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
	</main>

</body>
</html>