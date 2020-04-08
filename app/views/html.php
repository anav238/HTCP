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
			</ul>
		</nav>
		<div class="right">
			<div class="content">
				<div class="userArea">
					<div class="instructions">
						<h1>HTML Adventure - Level <?php echo $data->level; ?></h1>
						<p>
							<?php echo $data->description; ?>
						</p>
					</div>
					<div class="codeArea">
						<code>
							<?php echo $data->problem; ?>
						</code>
						<a class="button button-green">Submit</a>
					</div>
				</div>
				<div class="resultArea" id="luigiStory">
					Luigi's Adventure
					Tired of saving kingdoms and plumbing in his spare time, Luigi started to think about getting a career change.
					Guess what? He wants to become a web developer!
					After googling what are the steps you need to take to 
					learn web development, he arrived to the conclusion that he needs to learn HTML first. And you'll be
					part of this journey!
					<img src="<?=$GLOBALS['IMG_PATH']?>/luigi.png">
				</div>
			</div>
			
			<footer>

			</footer>
		</div>
		
	</main>
</body>
</html>