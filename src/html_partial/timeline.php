<section id="sectionPublication">
    <div id="newPublication">
        <!-- Form new article-->
        <form id="newPublicationForm" method="post" enctype="multipart/form-data" action="/new_article">
            <label id="publicationLabel" for="articleInput">Ecrivez votre message</label><br>
            <textarea id="articleInput" name="articleInput" type="text"></textarea>
            <div id="depose">Déposez vos images ou cliquez pour choisir</div>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg, image/png, image/gif, image/jpg">
            <div id="preview"></div>
            <button type="submit" id="submitPublication" >Envoyer</button>
            <button type="button" id="cancel">Annuler</button>
        </form>
    </div>
    <?php foreach ($articles as $article):
		$is_admin = false;
        foreach ($profiles as $profile) { 
            if ($profile["user_id"] === $article["user_id"]) {
                $profil_picture = $profile["profil_picture"];
                $first_name = $profile["first_name"];
                $last_name = $profile["last_name"];
                $status = $profile["status"]; // needs to be taken into account !!!
            }
        }
        foreach ($user_likes as $user_like) {
            if ($user_like["article_id"] === $article["article_id"]) {
                $like = "like.png";
                break;
            } else {
                $like = "unlike.png";
            }
        }
		if($article["page_id"] !== NULL) {
			foreach($name_picture_pages as $name_picture_page) {
				if($name_picture_page["page_id"] === $article["page_id"]) {
					$name = $name_picture_page["name"];
					$picture = $name_picture_page["picture"];
				}
			}
		} else if ($article["group_id"] !== NULL) {
			foreach ($name_picture_groups as $name_picture_group) {
				if($name_picture_group["group_id"] === $article["group_id"]) {
					$name = $name_picture_group["name"];
					$picture = $name_picture_group["picture"];
				}
			}
		};
		// articles
		if ($article["page_id"] === NULL && $article["group_id"] === NULL) {
			$show_name = $first_name . " " . $last_name;
			$show_picture = "img_profil/" . $profil_picture;
			$picture_id = "profil_picture";
			$action = "/profile";
			$actionId = "goToProfile";
			$action_name = "profil_id";
			$action_value = $article["user_id"];
		} else if ($article["page_id"] !== NULL){
			$show_name = $name;
			$show_picture =  "img_pages_groups/" . $picture;
			$picture_id = "picture";
			$action = "/public_page";
			$actionId = "goToPage";
			$action_name = "page_id";
			$action_value = $article["page_id"];
			foreach ($admins as $admin) {
				if ($admin["page_id"] === $article["page_id"]) {
					$is_admin = TRUE;
					break;
				}
			}
		} else {
			$show_name = $name;
			$show_picture =  "img_pages_groups/" . $picture;
			$picture_id = "picture";
			$action = "/group";
			$actionId = "goToGroup";
			$action_name = "group_id";
			$action_value = $article["group_id"];
		}; ?>
		<div id="article" style="margin-top:20px; border: solid 1px black; padding: 10px; width: 500px">
			<form id="<?= $actionId ?>" action="<?= $action ?>" method="post">
				<input type="hidden" name="<?= $action_name ?>" value="<?= $action_value ?>" />
				<button class="articleColor" type="submit" id="<?= $picture_id ?>" style="border:0; padding:5px;">
					<img id="profilPic" src="<?= $show_picture ?>" alt="" >
				</button>
				<button type="submit" class="articleColor" id="first_name" style="border:0; padding:0;"> 
					<?= $show_name ?> 
				</button>
			</form>
			<span id="date"><?= $article["date"] ?></span>
			<br>
			<span id="data"><?= $article["content"] ?></span>
			<br>
			<?php if($article["picture"]) :?>
				<img id="image_article" width="300px" src="img_post/<?=$article["picture"]?>" >
			<?php endif; ?>
			<form action="/like_article" method="post" id="like_article">
				<button class="articleColor" id="like_btn" type="submit" style="border: 0; padding:0px; margin: 5px;">
					<img style=" width: 40px; height: 40px; margin: 0px;" src="img_ressources/<?= $like ?>" alt="">
				</button>
				<span><?=$article["like_count"]?></span>
				<input type="hidden" name="like_article_id" value="<?= $article["article_id"] ?>">
			</form>
			<!-- conditions to modify or delete articles -->
			
			<?php if(($user_id === $article["user_id"] && $article["page_id"] === NULL) || ($article["page_id"] !== NULL
			&& $is_admin === true)) : ?>
				<form id="delete_article" method="post" action="/delete_article">
					<button type="submit" id="delete_btn">Supprimer</button>
					<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
					<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
				</form>
				<button type="button" id="open_modify_article">Modifier</button>
				<form id="form_modify_article" method="post" action="/modify_article">
					<label id="label_modify" for="modify_input">Ecrivez votre message</label>
					<textarea id="modify_article_input" type="text" name="modify_article" value=""><?= $article["content"] ?></textarea>
					<button id="modify_btn" type="submit">Valider</button>
					<input type="hidden" name="article_id" value="<?=$article["article_id"]?>">
					<input type="hidden" name="article_user" value="<?=$article["user_id"]?>">
				</form>
			<?php endif; ?>

			<button type="button" id="open_comment">Commenter</button>
			<section id="comment_section">
				<!-- require un truc ici -->
				<?php require __DIR__ . "/../php_partial/comment.php"?>
			</section>
		</div>



		<!-- partie supprimée (gardée dans brouillon_html.php par sécurité) -->



    <?php endforeach; ?>
	<section id="canHelp">
		<h4>Pages que vous pourriez aimer</h4>
		<?php foreach ($all_pages as $page): ?>
			<form id="goToPages" action="/public_page" method="post">
				<input type="hidden" name="page_id" value="<?= $page["page_id"] ?>" />
				<button type="submit" id="picture_page" class="baseProfile" style=" border:0; padding:0px;">
					<img id="profilPic" src="img_pages_groups/<?= $page["picture"] ?>" alt="" width="40px">
				</button>
				<button type="submit" id="first_name" class="baseProfile" style=" border:0; padding:0;"> 
					<?= $page["name"] ?> 
				</button>
			</form>
		<?php endforeach; ?>
		<h4>Groupes que vous pourriez aimer</h4>
		<?php foreach ($all_groups as $group): ?>
			<form id="goTogroups" action="/group" method="post">
				<input type="hidden" name="group_id" value="<?= $group["group_id"] ?>" />
				<button type="submit" id="picture_group" class="baseProfile" style=" border:0; padding:0px;">
					<img id="profilPic" src="img_pages_groups/<?= $group["picture"] ?>" alt="" width="40px">
				</button>
				<button type="submit" id="first_name" class="baseProfile"  style=" border:0; padding:0;"> 
					<?= $group["name"] ?> 
				</button>
			</form>
		<?php endforeach; ?>
		<h4>Personne que vous pourriez connaitre</h4>
		<?php foreach ($all_users as $all_user): ?>
			<?php if($all_user["user_id"] !== $user_id) : ?>
				<form id="goToProfil" action="/profile" method="post">
					<input type="hidden" name="profil_id" value="<?= $all_user["user_id"] ?>" />
					<button type="submit" id="picture_group" class="baseProfile" style="border:0; padding:0px;">
						<img id="profilPic" src="img_profil/<?= $all_user["profil_picture"] ?>" alt="" width="40px">
					</button>
					<button type="submit" id="first_name" class="baseProfile"  style=" border:0; padding:0;"> 
						<?= $all_user["first_name"] . " " . $all_user["last_name"]?> 
					</button>
				</form>
			<?php endif; ?>
		<?php endforeach; ?>
	</section>
	
</section>