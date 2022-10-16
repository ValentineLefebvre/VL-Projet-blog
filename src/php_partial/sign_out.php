<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_POST["deco"])) {
            // empty session
            unset($_SESSION["user"]);
            http_response_code(302);
            // go to login
            header('Location: /login');
            exit();
        }
    }
?>