<!DOCTYPE html>
<html>
<head>
	<title>collection booru</title>
	<style>
		body { font-family: sans-serif; }
		img, video { height: 200px; border: 2px solid transparent; }

		h1 { text-align: center; font-size: 4em; }
		h2 { border-bottom: 1px solid grey; }
	</style>
</head>
<body>

	<h1>[<a href="/">collection booru</a>]</h1>

	<div>
		<?php

			function addImagesInDir($dir) {

				echo "<h2>/$dir</h2>";
				
				$files = scandir($dir);

				foreach ($files as $file) {

					if (is_dir($file)) {
						
						if ($file != "." && $file != "..") {

							addImagesInDir($dir . "/" . $file);
						}

					} else {

						$file_extension = pathinfo($file)["extension"];

						if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

							$uri = str_replace(".", "*", str_replace("/", "$", $dir . "/" . $file));

							echo "<a href='$uri'><img src='" . $dir . "/" . $file . "'></a>";
						}

						if ($file_extension == "mp4") {

							echo "<video height='200' controls src='" . $dir . "/" . $file . "'></video>";
						}
					}
				}
			}

			$uri = str_replace("*", ".", str_replace("$", "/", $_SERVER["REQUEST_URI"]));

			if ($uri == "/") {

				addImagesInDir(".");

			} else {

				echo "<h2>" . $uri . "</h2>";

				$file_extension = pathinfo($uri)["extension"];

				if ($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "webp" || $file_extension == "gif") {

					echo "<img style='height: auto; max-height: 90vh; max-width: 100vw;' src='" . $uri . "'>";
				}

				if ($file_extension == "mp4") {

					echo "<video height='600' controls src='" . $uri . "'></video>";
				}
			}
		?>
	</div>

</body>
</html>