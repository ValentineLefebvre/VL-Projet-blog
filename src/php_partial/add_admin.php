<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
		if(isset($_POST["new_admin"])) {
            // get info from form
            require "../database/pdo.php";
            $page_id = filter_input(INPUT_POST, "new_admin_page");
            $profile_id = filter_input(INPUT_POST, "new_admin_account");
			var_dump($page_id);
			var_dump($profile_id);

			// delete relatioship from database
			$maRequete = $pdo->prepare("INSERT INTO `admins` (`page_id`,`user_id`) VALUES (:pageId, :profileId);");
			$maRequete->execute([
				":pageId" => $page_id,
				":profileId" => $profile_id
			]);

			// we need to make sure the page session is set before going back
			$maRequete = $pdo->prepare(
				"SELECT * FROM `pages` WHERE `page_id` = :pageId;");
				$maRequete->execute([
					":pageId" => $page_id
				]);
			$current_page = $maRequete->fetch();
			$_SESSION["page"] = $current_page;
						
			// go back to public_page
			http_response_code(302);
			header("Location: /public_page");
			exit();
		}
    }
?>