<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>HTML & CSS Adventure</title>
	<meta name="theme-color" content="#d11a0e">
	<!--
	<meta name="description" content="descriere relevanta"/>
	<link rel="icon" type="image/x-icon" href="assets/img/icons/512.png" />
	<link rel="manifest" href="assets/js/manifest.json">
	-->
	<link href="<?php echo getenv("CSS_PATH"); ?>/style.css" rel="stylesheet" />
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
			<li><a class="button button-red button-active" href="/css">CSS World</a></li>
			<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
			<li><a class="button button-blue" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main class="loading">
		<nav class="nav nav-red">
			<ul class="links">
				<li><a class="button button-green" href="/html">HTML World</a></li>
				<li><a class="button button-red button-active" href="/css">CSS World</a></li>
				<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
				<li><a class="button button-blue" href="/profile">Profile</a></li>
			</ul>
			<h1>CSS Challenges</h1>
			<ul>

			</ul>
		</nav>
		<div class="right">
			<div class="loading-info">
				<div class="loading-info-spinner"></div>
				<div class="loading-info-text">
					Loading level...
				</div>
			</div>
			<div class="instructions">
				<h1>CSS Adventure - Level <span></span></h1>
				<p></p>
			</div>
			<div class="code">
				<div class="codeArea">
					<code></code>
					<a class="button button-red" id="submit">Submit</a>
				</div>
				<div class="resultArea">
					<iframe class="css"></iframe>
				</div>
			</div>
			
			<footer>

			</footer>
		</div>
		
	</main>

	<script src="<?=getenv("JS_PATH")?>/script.js" type="text/javascript"></script>

</body>
</html>