<!DOCTYPE html>
<html>
<head>
	<title>localbooru</title>
	<style>
		body { font-family: sans-serif; margin: 0; display: grid; grid-template-columns: 20em 1fr; min-height: 100vh; font-size: 15px; color: #f1f1f1; background: #111014; }
		img, video { height: 200px; border: 2px solid transparent; }

		h1 { margin: 1em; padding: 1em; border: 1px solid #445; text-align: center; border-radius: 4px; }
		h2 { color: #888; font-size: 1em; }

		a { text-decoration: none; }
		a:hover { text-decoration: underline; }

		header, aside, main { padding: 1em; }

		aside { border-right: 1px solid #445; }
		aside a { color: inherit; display: block; padding: 0.5em; }
		aside a:hover { background: #222; }
	</style>
</head>
<body>

	<!-- php -S localhost:4444 -t . -->
	<!--
	layout inspired by https://webdesignerdepot-wp.s3.us-east-2.amazonaws.com/2023/11/27135129/01-current-DA-homepage.jpg
	and https://9gag.com/
	-->

	<aside>
		<h1>localbooru</h1>

		<div style="font-weight: 700;">
			<a href="/">Home</a>
			<a href="/">Random</a>
		</div>
		<br>

		<h2>Boards</h2>
		<?php
			$files = scandir("./boards");

			foreach ($files as $file) {

				if ($file != "." && $file != ".." && is_dir("./boards/$file")) {

					echo "<a href='/$file'>$file</a>";
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

						$uri = $board . "/" . $file;
						$file_extension = pathinfo($file)["extension"];

						if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

							echo "<a href='$uri'><img src='/boards/" . $board . "/" . $file . "'></a>";
						
						} else if ($file_extension == "mp4") {

							echo "<a href='$uri'><video height='200' src='/boards/$board/$file'></video></a>";
						}
					}
				}
			}

			$uri_elements = explode("/", $_SERVER["REQUEST_URI"]);

			if ($uri_elements[1] == "") {

				// home

				echo "<h2>Welcome to <span style='color: #59c;'>localbooru!</span></h2><p>We are currently serving [num] images.</p>";

			} else if (count($uri_elements) == 2) {

				// all items in a board

				echo "<h2>$uri_elements[1]</h2>";

				addBoardItems($uri_elements[1]);

			} else {

				// specific item

				echo "<h2>$uri_elements[1]/$uri_elements[2]</h2>";

				$file = "/boards/$uri_elements[1]/$uri_elements[2]";
				$file_extension = pathinfo($uri_elements[2])["extension"];

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