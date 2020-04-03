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
			<li><a class="button button-green" href="/HTCP/html">HTML World</a></li>
			<li><a class="button button-red" href="/HTCP/css">CSS World</a></li>
			<li><a class="button button-blue button-active" href="#">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main>
		<nav class="nav nav-blue">
			<img src="<?=$GLOBALS['IMG_PATH']?>/placeholder-profile-pic.png" class="profilePicture">
			<h1>Mario's profile</h1>
			<ul>
				<li><a href="#" class="button button-blue button-active">Personal statistics</a></li>
				<li><a href="#" class="button button-blue">Leaderboard</a></li>
			</ul>
		</nav>	
		<div class="right">
			<div class="profileData">
				
			</div>
			<footer>

			</footer>
		</div>
	</main>
	
</body>
</html>