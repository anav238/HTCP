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
	<link href="<?php echo $GLOBALS['CSS_PATH']; ?>/style.css" rel="stylesheet" />
	<script src="<?=$GLOBALS['JS_PATH']?>/script.js" type="text/javascript"></script>
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
			<li><a class="button button-blue" href="/profile">Profile</a></li>
		</ul>
		<div class="divider"></div>
	</header>
	<main>
		<nav class="nav nav-red">
			<ul class="links">
				<li><a class="button button-green" href="/html">HTML World</a></li>
				<li><a class="button button-red button-active" href="/css">CSS World</a></li>
				<li><a class="button button-blue" href="/profile">Profile</a></li>
			</ul>
			<h1>CSS Challenges</h1>
			<ul>
				<li><a href="#" class="button button-red button-active">Level 1</a></li>
				<li><a href="#" class="button button-red">Level 2</a></li>
				<li><a href="#" class="button button-red">Level 3</a></li>
				<li><a href="#" class="button button-red">Level 4</a></li>
                <li><a href="#" class="button button-red">Level 5</a></li>
                <li><a href="#" class="button button-red">Level 6</a></li>
                <li><a href="#" class="button button-red">Level 7</a></li>
                <li><a href="#" class="button button-red">Level 8</a></li>
                <li><a href="#" class="button button-red">Level 9</a></li>
                <li><a href="#" class="button button-red">Level 10</a></li>
			</ul>
		</nav>
		<div class="right">
			<div class="instructions">
				<h1>CSS Adventure - Level <?php echo $data->level; ?></h1>
				<p>
					<?php echo $data->description; ?>
				</p>
			</div>
			<div class="code">
				<div class="codeArea">
					<code>
						<?php echo $data->problem; ?>
					</code>
					<a class="button button-green">Submit</a>
				</div>
				<!-- <div class="resultArea" id="marioStory">
                    <img src="<?=$GLOBALS['IMG_PATH']?>/luigi.png" id="luigi">
                    <img src="<?=$GLOBALS['IMG_PATH']?>/mario.png" id="mario">
                    <img src="<?=$GLOBALS['IMG_PATH']?>/goomba.png" class="goomba">
                    <img src="<?=$GLOBALS['IMG_PATH']?>/goomba.png" class="goomba">
				</div> -->
				<div class="resultArea">
					<iframe></iframe>
				</div>
			</div>
			
			<footer>

			</footer>
		</div>
		
	</main>
</body>
</html>