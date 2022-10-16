<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Blog</title>
	<link rel="stylesheet" type="text/css" href="./public/style/style.css">
</head>
<body>
<?php
// require "../database/pdo.php";
$pdo = new PDO("mysql:host=database:3306;dbname=db_blog_docker", "root", "password");
$query = $pdo->query("SELECT `email` FROM `users`");
$users_emails = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<br>
<br>
<?php if($_SESSION===[]): ?> <!-- We only show the connexion / inscription forms if there's no session. -->
	<?php if(!empty($users_emails)): ?> <!-- We don't show the login form if the database is empty. -->
		<!-- Login -->
		<div class="block1">
			<form class="form" method="post">
				<div class="container">
					<div id="tripleLogin"> 
						<h1 class="loginF">CONNEXION</h1>
					</div>
					<div class="contSign">
						<label class="mail" for="email">Adresse mail :</label>
						<input class="inputSign" type="email" id="email" name="mail" placeholder="">
					</div>
					<div class="contSign">
						<label class="mdp" for="password">Mot de passe :</label>
						<input class="inputSign" type="password" id="password" name="mdp" placeholder="">
					</div>
					<div class="signButton">
						<button class="validate" type="submit" id="valider" value="Valider" name="connectSubmit" >Se connecter</button>
					</div>
				</div>
			</form>
		</div>
		<?php 
		if(isset($_POST['connectSubmit'])){
			if(!empty($_POST['email']) AND !empty($_POST['password'])){
				$mail = HTMLspecialchars($_POST['email']);
				$pwd = hash("sha512", filter_input(INPUT_POST, "password"));
				
				// get user info by checking mail
				$logInRequest = $pdo->prepare("SELECT `user_id`, `email`, `password`, `pseudo`, `admin` FROM `users` WHERE `email` = :email;");
				$logInRequest->execute([
					":email" => $mail,
				]);
				$user = $logInRequest->fetch();
				// if no result, error message
				if (!$user) {
					echo("Aucun compte ne correspond à cet email.");
				} else if ($user["password"] !== $pwd) {
					echo("Mot de passe incorrect.");
				} else { // if result, we set the session.
					$_SESSION['user_id'] = $user["user_id"];
					$_SESSION['pseudo'] = $user["pseudo"];
					$_SESSION['admin'] = $user["admin"];
				}
			} else {
				echo("Veuillez remplir tous les champs.");
			};
		};
		?>
	<?php endif; ?>
	<!-- Sign up -->
	<div class="block2">
		<form class="form" method="POST">
			<div class="containerSignUp">
				<div id="tripleLogin"> 
					<h1 class="loginF">INSCRIPTION</h1>
				</div>
				<div class="contSign">
						<label class="test" for="pseudo">Pseudo :</label>
						<input class="inputSign" type="text" id="pseudo" name="pseudo" placeholder="">
				</div>

				<div class="contSign">
						<label class="test" for="email">Email :</label>
						<input class="inputSign" type="email" id="email" name="email" placeholder="">
				</div>
				<div class="contSign">
					<label class="test" for="password">Password :</label>
					<input class="inputSign" type="password" id="password" name="password" placeholder="">
				</div>
				<div class="contSign">
						<label class="test" for="confirmPassword">Confirm password :</label>
						<input class="inputSign" type="password" id="confirmPassword" name="confirmPassword" placeholder="">
				</div>
					<div class="signButton">
						<button class="validate" type="submit" id="valider" value="Valider" name="signUpSubmit">S'inscrire</button>

					</div>
				</div>
			</div>
		</form>
	</div>
	<?php 
	if(isset($_POST['signUpSubmit'])){
		if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['confirmPassword']) AND ($_POST['password'] == $_POST['confirmPassword'])){
			$pseudo = HTMLspecialchars($_POST['pseudo']);
			$mail = HTMLspecialchars($_POST['email']);
			$pwd = hash("sha512", filter_input(INPUT_POST, "password"));
			

			if(empty($users_emails)){ // The first user is admin.
				$addUser = $pdo->prepare("INSERT INTO `users` (`email`,`password`,`pseudo`,`admin`) VALUES ( :email, :pwd, :pseudo, 'true')");
				$addUser->execute([
					":email" => $mail,
					":pwd" => $pwd,
					":pseudo" => $pseudo
				]);
				// Now we need the user_id that was just created (it's supposed to be 1).
				$newUserInfoRequest = $pdo->prepare("SELECT * FROM `users` where `email`=:email");
				$newUserInfoRequest->execute([
					":email" => $mail
				]);
				$newUserInfo = $newUserInfoRequest -> fetch();
				// And we set the session.
				$_SESSION['user_id'] = $newUserInfo["user_id"];
				$_SESSION['pseudo'] = $pseudo;
				$_SESSION['admin'] = $newUserInfo["admin"];
			} else {
				$mailDoesntExist = true;
				foreach ($users_emails as $users_email) { 
					if ($users_email["email"] === $mail) {
						echo("Un compte avec cet email existe déjà.");
						$mailDoesntExist = false;
					};
				};
				if($mailDoesntExist===true){
					$addUser = $pdo->prepare("INSERT INTO `users` (`email`,`password`,`pseudo`) VALUES (:email, :pwd, :pseudo)");
								$addUser->execute([
									":email" => $mail,
									":pwd" => $pwd,
									":pseudo" => $pseudo
								]);
					
					// Now we need the user_id that was just created.
					$newUserInfoRequest = $pdo->prepare("SELECT * FROM `users` where `email`=:email");
					$newUserInfoRequest->execute([
						":email" => $mail
					]);
					$newUserInfo = $newUserInfoRequest -> fetch();
					var_dump($newUserInfo);
					// And we set the session.
					$_SESSION['user_id'] = $newUserInfo["user_id"];
					$_SESSION['pseudo'] = $pseudo;
					$_SESSION['admin'] = $newUserInfo["admin"];
			
				};			
			};
		} else if($_POST['password'] != $_POST['confirmPassword']) {
			echo("Les mots de passe ne correspondent pas.");
		} else {
			echo("Veuillez compléter tous les champs.");
		};
	};
	?>


<?php else: ?> <!-- If there's an ongoing session -->
	<form class="form" method="POST">
		<button class="validate" type="submit" id="valider" value="Valider" name="logOut">Déconnexion</button>
	</form>
	<?php 
	if(isset($_POST['logOut'])){
		$_SESSION=[];
	};
	?>
	<!-- The articles are supposed to be here -->
<?php endif; ?>


</body>
</html>
