<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>HTML & CSS Adventure</title>
	<meta name="theme-color" content="#008529">
	<meta name="description" content="Join your childhood retro games heroes on their journeys, discovering the magic of web development using the power of code!"/>
	<link href="<?=getenv("CSS_PATH")?>/style.css" rel="stylesheet" />
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
			<li><a class="button button-green button-active" href="/html">HTML World</a></li>
			<li><a class="button button-red" href="/css">CSS World</a></li>
			<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
			<li><a class="button button-blue" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main class="loading">
		<nav class="nav nav-green">
			<ul class="links">
				<li><a class="button button-green button-active" href="/html">HTML World</a></li>
				<li><a class="button button-red" href="/css">CSS World</a></li>
				<li><a class="button button-pink" href="/leaderboard">Leaderboard</a></li>
				<li><a class="button button-blue" href="/profile">Profile</a></li>
			</ul>
			<h1>HTML Challenges</h1>
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
				<h1>HTML Adventure - Level <span></span></h1>
				<p></p>
			</div>
			<div class="code">
				<div class="codeArea">
					<code></code>
					<?php
						if ($GLOBALS["exerciseStatistics"] == true) 
							echo "<div class=\"attempts\"></div>";
					?>
					<a class="button button-green" id="submit">Submit</a>
				</div>
				<div class="resultArea">
					<iframe></iframe>
				</div>
			</div>
			
			<footer>

			</footer>
		</div>
	</main>

	<script src="<?=getenv("JS_PATH")?>/script.js"></script>

</body>
</html>