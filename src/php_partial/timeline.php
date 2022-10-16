<?php

// start buffering
ob_start();
$title = "Fakebook - fil d'actualité";

require_once "../database/pdo.php";
$user_id = $_SESSION["user"]["user_id"];

$user_name = $_SESSION["user"]["first_name"] . " " . $_SESSION["user"]["last_name"];

$articles = [];
// user's articles
$maRequete = $pdo->prepare("SELECT * from `articles` WHERE `user_id` = :userId AND `page_id` IS NULL AND `group_id` IS NULL;");
$maRequete->execute([
	":userId" => $user_id
]);
$user_articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$articles = array_merge($articles, $user_articles);

// friends' articles (except those they wrote as admins)
$maRequete = $pdo->prepare(
	"SELECT * from `articles`
	WHERE (`user_id` IN (SELECT `user_id_a` FROM `relationships` WHERE `user_id_b` = :userId AND `blocked` = 'no' AND `status` = 'approved') AND `page_id` IS NULL AND `group_id` IS NULL)
	OR (`user_id` IN (SELECT `user_id_b` FROM `relationships` WHERE `user_id_a` = :userId AND `blocked` = 'no' AND `status` = 'approved') AND `page_id` IS NULL AND `group_id` IS NULL)");
$maRequete->execute([
	":userId" => $user_id
]);
$friends_articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$articles = array_merge($articles, $friends_articles);

// followed pages' articles
$maRequete = $pdo->prepare(
	"SELECT * from `articles` WHERE `page_id` IN (SELECT `page_id` FROM `followers` WHERE `user_id`= :userId AND `banned`='no')");
$maRequete->execute([
	":userId" => $user_id
]);
$pages_articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$articles = array_merge($articles, $pages_articles);

// groups' articles
$maRequete = $pdo->prepare(
	"SELECT * from `articles` WHERE `group_id` IN (SELECT `group_id` FROM `members` WHERE `user_id`= :userId AND `status` = 'approved')");
$maRequete->execute([
	":userId" => $user_id
]);
$groups_articles = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$articles = array_merge($articles, $groups_articles);

usort($articles, function ($a, $b) {
	if ($a['date'] > $b['date']) return -1;
	if ($a['date'] < $b['date']) return 1;
	return 0;
});

$maRequete = $pdo->prepare(
	"SELECT `name`, `picture`, `page_id` FROM `pages` WHERE (`page_id` IN (SELECT `page_id` FROM `admins` WHERE `user_id` = :userId)) OR (`page_id` IN (SELECT `page_id` FROM followers WHERE `user_id` = :userId))");
$maRequete->execute([
	":userId" => $user_id
]);
$name_picture_pages = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare(
	"SELECT `name`, `picture`, `group_id` FROM `groups` WHERE (`group_id` IN (SELECT `group_id` FROM `admins` WHERE `user_id` = :userId)) OR (`group_id` IN (SELECT `group_id` FROM `members` WHERE `user_id` = :userId))");
$maRequete->execute([
	":userId" => $user_id
]);
$name_picture_groups = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT `page_id`, `user_id` FROM `admins` WHERE `user_id` = :userId;");
$maRequete->execute([
	":userId" => $user_id
]);
$admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);


$maRequete = $pdo->prepare("SELECT `user_id`, `profil_picture`, `first_name`, `last_name`, `status` FROM `users` "); // add condition for relationship
$maRequete->execute();
$profiles = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT * FROM `likes` WHERE `user_id` = :userId");
$maRequete->execute([
	":userId" => $user_id
]);
$user_likes = $maRequete->fetchAll(PDO::FETCH_ASSOC);
$like = "unlike.png";

$maRequete = $pdo->prepare("SELECT * FROM `pages`");
$maRequete->execute();
$all_pages = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT * FROM `groups`");
$maRequete->execute();
$all_groups = $maRequete->fetchAll(PDO::FETCH_ASSOC);

$maRequete = $pdo->prepare("SELECT * FROM `users`");
$maRequete->execute();
$all_users = $maRequete->fetchAll(PDO::FETCH_ASSOC);



require_once __DIR__ . "/../html_partial/timeline.php";
// clean buffering in $content
$content = ob_get_clean();

?>