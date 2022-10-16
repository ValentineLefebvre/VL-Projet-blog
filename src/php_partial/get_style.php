<!-- select right mode by checking user session -->
<?php if(isset($_SESSION["user"]['user_id']) && $_SESSION["user"]['theme'] == 1) {?>
    <link rel="stylesheet" type="text/css" href="/style/darkmode.css?<?php echo time();?>">
<?php } else { ?>
    <link rel="stylesheet" type="text/css" href="/style/lightmode.css?<?php echo time();?>">
<?php } ?>