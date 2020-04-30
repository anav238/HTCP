<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>HTML & CSS Adventure</title>
	<meta name="theme-color" content="#020307">
	<!--
	<meta name="description" content="descriere relevanta"/>
	<link rel="icon" type="image/x-icon" href="assets/img/icons/512.png" />
	<link rel="manifest" href="assets/js/manifest.json">
	-->
	<link href="<?=$GLOBALS['CSS_PATH']?>/style.css" rel="stylesheet" />
</head>
<body>
	<header>
		<a class="site-title" href="/">
			<small>
				HTML & CSS
			</small>
			<br />
			Adventure
		</a>
		<ul class="links">
			<li><a class="button button-green button-active" href="#">HTML World</a></li>
			<li><a class="button button-red" href="/css">CSS World</a></li>
			<li><a class="button button-blue" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main>
		<nav class="nav nav-green">
			<h1>HTML Challenges</h1>
			<ul>
				<li><a href="/html/1" class="button button-green button-active">Level 1</a></li>
				<li><a href="/html/2" class="button button-green">Level 2</a></li>
				<li><a href="/html/3" class="button button-green">Level 3</a></li>
				<li><a href="/html/4" class="button button-green">Level 4</a></li>
				<li><a href="/html/5" class="button button-green">Level 5</a></li>
				<li><a href="/html/5" class="button button-green">Level 6</a></li>
				<li><a href="/html/5" class="button button-green">Level 7</a></li>
				<li><a href="/html/5" class="button button-green">Level 8</a></li>
				<li><a href="/html/5" class="button button-green">Level 9</a></li>
				<li><a href="/html/5" class="button button-green">Level 10</a></li>
			</ul>
		</nav>
		<div class="right">
			<div class="content">
				<div class="userArea">
					<div class="instructions">
						<h1>HTML Adventure - Level <span></span></h1>
						<p></p>
					</div>
					<div class="codeArea">
						<code></code>
						<a class="button button-green" id="submit">Submit</a>
					</div>
				</div>
				<iframe class="resultArea">
				</iframe>
			</div>
			
			<footer>

			</footer>
		</div>
		
	</main>

	<script src="<?=$GLOBALS['JS_PATH']?>/script.js"></script>

</body>
</html>