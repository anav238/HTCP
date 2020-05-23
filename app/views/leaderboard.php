<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>HTML & CSS Adventure</title>
	<meta name="theme-color" content="#e11d78">
	<!--
	<meta name="description" content="descriere relevanta"/>
	<link rel="icon" type="image/x-icon" href="assets/img/icons/512.png" />
	<link rel="manifest" href="assets/js/manifest.json">
	-->
	<link href="<?=$GLOBALS['CSS_PATH']?>/style.css" rel="stylesheet" />
</head>
<body>
	<header>
		<div class="hamburger">
			<div class="hamburger-line"></div>
		</div>
		<a class="site-title" href="/">
			<small>
				HTML & CSS
			</small>
			<br />
			Adventure
		</a>
		<ul class="links">
			<li><a class="button button-green" href="/html">HTML World</a></li>
			<li><a class="button button-red" href="/css">CSS World</a></li>
			<li><a class="button button-pink button-active" href="/leaderboard">Leaderboard</a></li>
			<li><a class="button button-blue" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main class="loading">
		<nav class="nav nav-pink nav-profile">
			<ul class="links">
				<li><a class="button button-green" href="/html">HTML World</a></li>
				<li><a class="button button-red" href="/css">CSS World</a></li>
				<li><a class="button button-pink button-active" href="/leaderboard">Leaderboard</a></li>
				<li><a class="button button-blue" href="/profile">Profile</a></li>
			</ul>
		</nav>	
		<div class="right right-profile">
			<div class="loading-info">
				<div class="loading-info-spinner"></div>
				<div class="loading-info-text">
					Loading leaderboard...
				</div>
			</div>
			<div class="leaderboard">
				<div>
					<h1>Corectness Leaderboard</h1>
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Score</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div>
					<h1>Speed Leaderboard</h1>
					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Score</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<footer>

			</footer>
		</div>
	</main>
	
	<script src="<?=$GLOBALS['JS_PATH']?>/script.js" type="text/javascript"></script>

</body>
</html>