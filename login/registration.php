<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>

<?php
if(isset($_SESSION["name"])){
    header('Location: ../index.php');
}else{
    ?>
    <form action="registration_handler.php" method="post">
        <!-- <input type="text" name="name" id="name" placeholder="name" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+">
        <br>
        <input type="text" name="login" id="login" placeholder="login" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+">
        <br>
        <input type="text" name="pass" id="pass" placeholder="password" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+">
        <br> -->

        <input type="text" name="name" id="name" placeholder="name" required pattern="[a-zA-Z0-9]+">
        <br>
        <input type="text" name="login" id="login" placeholder="login" required  pattern="[a-zA-Z0-9]+">
        <br>
        <input type="text" name="pass" id="pass" placeholder="password" required pattern="[a-zA-Z0-9]+">
        <br>
        <input type="email" name="email" id="email" placeholder="email" required>
        <br>

        <button type="submit">Creat Account</button>
    </form>
    You already have an account
    <a href="index.php">Log in</a>
    <?php
}
?>
</body>
</html>