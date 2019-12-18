<?php
$app_name = "Movie Catalogue";

$api_key = "75103a65bd5c254a1120b467ed4ca64b"."";
$language = "en-US";
$url = "https://api.themoviedb.org/3/movie/popular";

$request_url = $url."?api_key=".$api_key."&language=".$language;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?= $app_name; ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style type="text/css">
		html, body {
			margin: 0;
			padding: 0;
			background-color: #efefef;
		}

		.hidden {display: none;}

		.card-img-top {
			filter: blur(.7px);
		}
		.card-img-top:hover {
			filter: blur(0);
		}

		.card-poster {
			margin-top: -6em;
			margin-left: 1.5em;
			z-index: 9;
			width: 5em;
		}

		.card-rating {
			color: #ffd700;
			font-size: 1.5em;
		}

		@media(max-width: 450px) {
			.card-poster {
				margin-top: -9em;
				width: 7em;
			}
		}
	</style>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
	<script type="text/javascript" src="liff-starter.js"></script>
</head>
<body>
	<nav class="navbar navbar-dark bg-dark">
		<span class="navbar-brand mb-0 h1"><?= $app_name; ?></span>
	</nav>

	<div class="container">
		<!--Konten LIFF v2-->
 
        <div id="liffAppContent">
			<!-- ACTION BUTTONS -->
			<div class="buttonGroup">
				<div class="buttonRow">
					<button id="openWindowButton" class="btn btn-success btn-small">Open External Window</button>
					<button id="closeWindowButton" class="btn btn-danger btn-small">Close LIFF App</button>
					<button id="sendMessageButton" class="btn btn-warning btn-small">Send Message</button>
				</div>
			</div>
 
			<!-- LIFF DATA -->
			<div id="liffData">
				<h3 id="liffDataHeader" class="textLeft">Informasi:</h3>
				<table>
					<tr>
						<th>isInClient : </th>
						<td id="isInClient" class="textLeft"></td>
					</tr>
					<tr>
						<th>isLoggedIn : </th>
						<td id="isLoggedIn" class="textLeft"></td>
					</tr>
				</table>
			</div>

			<!-- LOGIN LOGOUT BUTTONS -->
			<div class="buttonGroup">
				<button id="liffLoginButton" class="btn btn-success btn-small">Log in</button>
				<button id="liffLogoutButton" class="btn btn-warning btn-small">Log out</button>
			</div>

			<div id="statusMessage">
			<div id="isInClientMessage"></div>
			<div id="apiReferenceMessage">
				<p>Available LIFF methods vary depending on the browser you use to open the LIFF app.</p>
				<!-- <p>Please refer to the
					<a href="https://developers.line.biz/en/reference/liff/#initialize-liff-app">API reference page</a>
					for more information.
				</p> -->
			</div>
			</div>
		</div>

		<!-- LIFF ID ERROR -->
		<div id="liffIdErrorMessage" class="hidden">
			<p>You have not assigned any value for LIFF ID.</p>
			<p>If you are running the app using Node.js, please set the LIFF ID as an environment variable in your
			Heroku account follwing the below steps: </p>

			<code id="code-block">
				<ol>
					<li>Go to `Dashboard` in your Heroku account.</li>
					<li>Click on the app you just created.</li>
					<li>Click on `Settings` and toggle `Reveal Config Vars`.</li>
					<li>Set `MY_LIFF_ID` as the key and the LIFF ID as the value.</li>
					<li>Your app should be up and running. Enter the URL of your app in a web browser.</li>
				</ol>
			</code>

			<p>If you are using any other platform, please add your LIFF ID in the <code>index.html</code> file.</p>
			<p>For more information about how to add your LIFF ID, see
				<a href="https://developers.line.biz/en/reference/liff/#initialize-liff-app">Initializing the LIFF app</a>.
			</p>
		</div>

		<!-- LIFF INIT ERROR -->
		<div id="liffInitErrorMessage" class="hidden">
			<p>Something went wrong with LIFF initialization.</p>
			<p>LIFF initialization can fail if a user clicks "Cancel" on the "Grant permission" screen, or if an error occurs in the process of <code>liff.init()</code>.
		</div>

		<!-- NODE.JS LIFF ID ERROR -->
		<div id="nodeLiffIdErrorMessage" class="hidden">
			<p>Unable to receive the LIFF ID as an environment variable.</p>
		</div>
	</div>

	<div class="container my-4">
		<div class="row">
			<?php
			if(isset($response)) {
				$response = $response['results'];

				$img_url = "https://image.tmdb.org/t/p/w300/";

				foreach ($response as $resp) {
					$poster = $img_url.$resp['poster_path'];
					$backdrop = $img_url.$resp['backdrop_path'];

					if($resp['backdrop_path'] === null) {
						$backdrop = "assets/img/dummy_backdrop.png";
					}
			?>
					<div class="col-md-3 my-2">
						<div class="card">
							<img class="card-img-top" src="<?= $backdrop; ?>" alt="<?= str_replace(' ', '_', $resp['title']); ?>.jpg">
							<img class="card-poster" src="<?= $poster; ?>">

							<div class="card-body">
								<h4 class="mb-0"><?= $resp['title']; ?></h4>
								<p class="card-text my-0"><span class="card-rating">&starf;</span> <small><?= $resp['vote_average']; ?></small></p>
								<p class="card-text"><?= $resp['overview']; ?></p>
							</div>
						</div>
					</div>
			<?php
				}
			}
			?>
		</div>
	</div>

	<footer class="footer-copyright bg-dark text-center text-white py-2">
		<small>&copy; 2019 <?= $app_name; ?></small>
	</footer>

	<script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
	<script type="text/javascript" src="liff-starter.js"></script>
</body>
</html>
