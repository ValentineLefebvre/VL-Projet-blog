<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="titre"><?=$title?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/style/style.css?<?php echo time();?>">
    <!-- require dark mode or light mode -->
    <?php require_once __DIR__ . "/../php_partial/get_style.php";?>
</head>
<body>
    <header>
    <?php 
        // if a user is connected and if url not egal to login or sign_up
        // we show the nav bar
        if ($uri != "/login" && $uri != "/sign_up" && $uri != "/inactive"):
            if(isset($_SESSION["user"]["user_id"])) :?>
				<!-- link to the current user's profile page -->
				<form id="goToProfile" action="/profile" method="post">
					<input type="hidden" name="profil_id" value="<?= $_SESSION["user"]["user_id"] ?>" />
					<button class="baseProfile" type="submit" id="profil_picture" style=" border:0; padding:5px;">
						<img id="profilPic" src="img_profil/<?=  $_SESSION["user"]["profil_picture"] ?>" alt="" width="40px">
					</button>
					<button class="baseProfile" type="submit" id="first_name" style="border:0; padding:0;"> 
						<?=$_SESSION["user"]["first_name"] . " " . $_SESSION["user"]["last_name"]?> 
					</button>
				</form>
        		<div id="baseHtml">
                    <br>
                <form id="deco_form" method="post" action="/notifications">
                    <div class="baseHtml">
                        <button class="baseButton" class="nav_deco"id="notifications" type="submit">Notification</button>
                        <input type="hidden" name="notif">
                    </div>
                </form>
				<form id="deco_form" method="post" action="/sign_out">
                    <div class="baseHtml">
                        <button class="baseButton" class="nav_deco"id="deconnexion" type="submit">Deconnexion</button>
                        <input type="hidden" name="deco">
                    </div>
                </form>
                <div class="baseHtml">
                    <form id="someone" method="post" action="/search">
                        <label id="someone" for="someone"></label>
                        <input id="someone" type="text" name="someone">
                        <button class="baseButton" id="someone" type="submit">Chercher une personne</button>
                    </form>
                </div>
            <div class="baseHtml">
                <button class="baseButton" ><a style="text-decoration: none; color: black;" href="/timeline">Fil d'actualité</a></button>
            </div>
            <div class="baseHtml">
                    <form id="page" method="post" action="/search">
                        <label id="page" for="page"></label>
                        <input id="page" type="text" name="page">
                        <button class="baseButton" id="page" type="submit">Chercher une page</button>
                    </form>
                </div>
            <div class="baseHtml">
                <button class="baseButton" ><a style="text-decoration: none; color: black;" href="/conversation">Fakenger</a></button>
            </div>
                <div class="baseHtml">
                    <form id="group" method="post" action="/search">
                        <label id="group" for="group"></label>
                        <input id="group" type="text" name="group">
                        <button class="baseButton" id="group" type="submit">Chercher un groupe</button>
                    </form>
                </div>
        </div>
            <?php endif; 
        endif; ?>
            

        
    </header>
    <main>
        <?php if(!isset($content)):?>
            <h4>Vous vous êtes perdu</h4>
            <a href="/timeline">fil d'actualité</a>
        <?php else :
            //show $content here 
            echo $content;
        endif  ?>
    </main>

    <footer>
        <!-- require the right script by checking url -->
        <?php require_once __DIR__ . "/../php_partial/get_script.php"; ?>
    </footer>
</body>
</html>