<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

$isBoardOpen= false;

if(isset($_GET["board"])){
    if(is_numeric($_GET["board"])){

        include 'src/cfg/conn.php';

        $sql = "SELECT userboard.id_user, userboard.owner, userboard.edit, userboard.add_users, userboard.kick_users FROM userboard WHERE userboard.id_board='".$_GET["board"]."'";

        $res = @mysqli_query($conn, $sql);

        $users = $res->num_rows;

        if($users>0){
            $row = $res->fetch_assoc(); 
            if($_SESSION['id_user'] == $row['id_user']){
                $_SESSION['board'] = $_GET["board"];
                $_SESSION['owner'] = $row['owner'];
                $_SESSION['edit'] = $row['edit'];
                $_SESSION['add_users'] = $row['add_users'];
                $_SESSION['kick_users'] = $row['kick_users'];
                $isBoardOpen = true;
            }
        }

        mysqli_free_result($res);
        mysqli_close($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="isBoardOpen" content="<?php echo $isBoardOpen ?>">
    <title>Organizer</title>

    <link rel="stylesheet" href="src/css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="src/js/app.js"></script>
</head>
<body>
    <a href="index.php" id="list">list</a>
    <div id="main">
        <div id="app"></div>
        <div id="update"></div>
    </div>

    <form action="login/login_handler.php" method="post">
        <input type="submit" value="logout" name="logout">
    </form>
</body>
</html>