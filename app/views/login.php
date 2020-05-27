<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/0e5e6d76c0.js" crossorigin="anonymous"></script>
    <title>Login</title>
    <link href="<?=getenv("CSS_PATH")?>/style.css" rel="stylesheet" />
</head>
<body class="sign-in">
    <main>
        <div>
            <h1>HTML & CSS <br><span>Arcade</span></h1>
            <a href="https://github.com/login/oauth/authorize?client_id=<?php echo getenv("GITHUB_CLIENT_ID"); ?>"><button class="button button-red"><i class="fab fa-github"></i>Sign in with Github</button></a>
        </div>
        <footer>

        </footer>
    </main>
</body>
</html>