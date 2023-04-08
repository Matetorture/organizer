<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
if(isset($_SESSION["name"])){
    header('Location: ../index.php');
}else{
    ?>
    <form action="login_handler.php" method="post">
        <!-- <input type="text" name="login" id="login" placeholder="login" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+"> -->
        <input type="text" name="login" id="login" placeholder="login" required maxlength="32" pattern="[a-zA-Z0-9]+">
        <br>
        <!-- <input type="text" name="pass" id="pass" placeholder="haslo" required minlength="8" maxlength="32" pattern="[a-zA-Z0-9]+"> -->
        <input type="text" name="pass" id="pass" placeholder="haslo" required maxlength="32" pattern="[a-zA-Z0-9]+">
        <br>
        <button type="submit">Zaloguj</button>
    </form>
    <?php
}
?>
</body>
</html>