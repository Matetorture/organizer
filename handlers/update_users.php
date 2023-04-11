<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(isset($_POST['delete-user'])){

        include '../src/cfg/conn.php';

        $sql = "DELETE FROM `userboard` WHERE `userboard`.`id_user` = ".$_POST['delete-user']."";
    
        $res = @mysqli_query($conn, $sql);

        mysqli_close($conn);

        header('Location: ../login/index.php');
    }else{
        if(!isset($_POST["users"])){
            header('Location: ../login/index.php');
        }else{
    
            include '../src/cfg/conn.php';
    
            $sql = "UPDATE userboard SET edit = '".$_POST['users']['edit']."', add_users = '".$_POST['users']['addUsers']."', edit_users = '".$_POST['users']['editUsers']."', kick_users = '".$_POST['users']['kickUsers']."' WHERE id_user = ".$_POST['users']['id']." and owner = 0;";
        
            $res = @mysqli_query($conn, $sql);
    
            mysqli_close($conn);
        }
    }
}
?>