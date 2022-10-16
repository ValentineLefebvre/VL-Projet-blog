<!-- select right script by checking url -->
<?php if($uri === "/entrer.php") :?>
    <!-- script here -->
<?php elseif($uri === "/login") :?>
    <!-- script here -->
<?php elseif($uri === "/timeline") :?>
    <script src="script/timeline_script.js?<?php echo time(); ?>"></script>
<?php endif; ?>