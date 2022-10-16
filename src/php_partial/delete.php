<?php
ob_start();
if ("POST" === $_SERVER["REQUEST_METHOD"]) {
    require_once __DIR__ . "/../database/pdo.php"; 
    $page_id = $_SESSION["page"]["page_id"];
    $user_id = $_SESSION["user"]["user_id"];
    $page_id = $_SESSION["page"]["page_id"];
    


    if($_POST['disable']){
        $maRequete=$pdo->prepare("UPDATE `users` SET status = 'inactive' WHERE `user_id` = $user_id;");
        $maRequete->execute();
        header("Location: /login");
    }

    elseif($_POST['delete']){
        $maRequete = $pdo->prepare("DELETE FROM `admins` WHERE `page_id` = :pageId AND `user_id` = :userId;");
        $maRequete->execute([
            ":pageId" => $page_id,
            ":userId" => $user_id
        ]);
        $maRequete = $pdo->prepare("SELECT `user_id` FROM `admins` WHERE `page_id` = :pageId;");
        $maRequete->execute([
            ":pageId" => $page_id
        ]);
        $admins = $maRequete->fetchAll(PDO::FETCH_ASSOC);
        if (count($admins) === 0){
            $maRequete = $pdo->prepare("DELETE FROM `pages` WHERE `page_id` = :pageId;");
            $maRequete->execute([
                ":pageId" => $page_id,
            ]);
        }
        $maRequete = $pdo->prepare("DELETE FROM `users` WHERE `user_id` = :user;");
        $maRequete->execute([
            ":user" => $user_id,
        ]);
    }
}

unset($_SESSION["user"]);
http_response_code(302);
// je redirige vers /login
header('Location: /login');
exit();
?>