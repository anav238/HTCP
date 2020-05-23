<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Profile - HTML & CSS Adventure</title>
	<meta name="theme-color" content="#2f70b7">
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
			<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
			<li><a class="button button-blue button-active" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main class="loading">
		<nav class="nav nav-blue nav-profile">
			<ul class="links">
				<li><a class="button button-green" href="/html">HTML World</a></li>
				<li><a class="button button-red" href="/css">CSS World</a></li>
				<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
				<li><a class="button button-blue button-active" href="/profile">Profile</a></li>
			</ul>
		</nav>	
		<div class="right right-profile">
			<div class="loading-info">
				<div class="loading-info-spinner"></div>
				<div class="loading-info-text">
					Loading profile data...
				</div>
			</div>
			<div class="profileData">
				<img src="/public/assets/img/placeholder-profile-pic.png" class="profilePicture">
				<h1></h1>
				<!-- <span>Register date</span> -->
				<div class="stats">
					<div>
						<h2>HTML Levels completed</h2>
						<span></span>
					</div>
					<div>
						<h2>CSS Levels completed</h2>
						<span></span>
					</div>
					<div>
						<h2>Speed Score</h2>
						<span></span>
					</div>
					<div>
						<h2>Correctness Score</h2>
						<span></span>
					</div>
				</div>
			</div>
			<footer>

			</footer>
		</div>
	</main>
	
	<script src="<?=$GLOBALS['JS_PATH']?>/script.js" type="text/javascript"></script>

</body>
</html>