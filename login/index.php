<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body>

<?php
if(isset($_SESSION["name"])){
    header('Location: ../index.php');
}else{
    ?>
    <div class="login-form">
        <form action="login_handler.php" method="post">
            <!-- <input type="text" name="login" id="login" placeholder="login" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+"> -->
            <input type="text" name="login" id="login" placeholder="login" required maxlength="32" pattern="[a-zA-Z0-9]+">
            <br>
            <!-- <input type="text" name="pass" id="pass" placeholder="password" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+"> -->
            <input type="text" name="pass" id="pass" placeholder="password" required maxlength="32" pattern="[a-zA-Z0-9]+">
            <br>
            <br>
            <button type="submit">Log in</button>
        </form>
        <br>
        <br>
        Don't have an account
        <a href="registration.php" class="login-switch-link">Creat Account</a>
    </div>
    <?php
}
?>
</body>
</html>