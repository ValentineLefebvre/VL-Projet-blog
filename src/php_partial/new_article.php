<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["articleInput"])) {
            require_once __DIR__ . "/../database/pdo.php";
            $text = filter_input(INPUT_POST, "articleInput");
            $user_id = $_SESSION["user"]["user_id"];
            if($_FILES["fileToUpload"]["name"]) {
                require_once __DIR__ . "/upload_img_post.php";
            } else {
				// 1st we create the article in the articles table
                $maRequete = $pdo->prepare(
                    "INSERT INTO `articles` (`content`, `user_id`)
                    VALUES(:content, :userId)");
                    $maRequete->execute([
                        ":content" => $text,
                        ":userId" => $user_id
                    ]);

				$maRequete = $pdo->prepare(
                    "UPDATE `stats`
					SET `nb_articles` = `nb_articles` + 1
					WHERE `user_id` = :userId;");
                    $maRequete->execute([
                        ":userId" => $user_id
                    ]);
					
                http_response_code(302);
                // get previous pages and go
                $direction = explode("/",$_SERVER["HTTP_REFERER"]);
                if($direction[3] === "profile") {
                    header("Location: /profile");
                } else if ($direction[3] === "timeline") {
                    header("Location: /timeline");
                }
                exit();
            }
        }
    }
?>