<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../../login/index.php');
}else{
    if(isset($_GET['name'])){
        if(preg_match ("/^[A-Z0-9]+/i", $_GET['name']) && strlen($_GET['name']) >= 4 && strlen($_GET['name']) <= 32){
    
            include '../../src/cfg/conn.php';
            
            $sql = "INSERT INTO `board` (`id_board`, `name`, `date`) VALUES (NULL, '".$_GET['name']."', '".date("Y-m-d")."')";
            
            $res = @mysqli_query($conn, $sql);

            $sql = "INSERT INTO `userboard` (`id_userboard`, `id_board`, `id_user`, `owner`, `edit`, `add_users`, `edit_users`, `kick_users`) VALUES (NULL, '".mysqli_insert_id($conn)."', '".$_SESSION['id_user']."', '1', '1', '1', '1', '1');";
            
            $res = @mysqli_query($conn, $sql);

            mysqli_close($conn);
        }
        
        header('Location: ../../index.php');
    }
}
?>