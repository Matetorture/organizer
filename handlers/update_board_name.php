<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(isset($_POST['id']) && isset($_POST['boardName'])){
        if(preg_match("/^[A-Z0-9]+/i", $_POST['boardName']) && is_numeric($_POST['id']) && strlen($_POST['boardName']) >= 4 && strlen($_POST['boardName']) <= 32){

            include '../src/cfg/conn.php';
    
            $sql = "SELECT owner FROM userboard WHERE id_board =".$_POST['id']." AND id_user = ".$_SESSION['id_user'];
        
            $res = @mysqli_query($conn, $sql);

            $is_owner = $res->num_rows;
            mysqli_free_result($res);

            if($is_owner){

                $sql = "UPDATE `board` SET `name` = '".$_POST['boardName']."' WHERE `board`.`id_board` = ".$_POST['id'].";";

                $res = @mysqli_query($conn, $sql);

                mysqli_close($conn);
            }
        }
    }
}
?>