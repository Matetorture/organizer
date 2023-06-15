<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../../login/index.php');
}else{
    if(isset($_GET['board'])){
        $board = $_GET['board'];
    }
    else if(isset($_SESSION['board'])){
        $board = $_SESSION['board'];
    }
    if(isset($board)){

        include '../../src/cfg/conn.php';
    
        $sql = "SELECT owner FROM userboard WHERE id_board =".$board." AND id_user = ".$_SESSION['id_user'];
    
        $res = @mysqli_query($conn, $sql);

        $is_owner = $res->num_rows;
        mysqli_free_result($res);

        if($is_owner){

            $sql = "DELETE FROM `userboard` WHERE `userboard`.`id_board` = ".$board;
    
            $res = @mysqli_query($conn, $sql);

            $sql = "DELETE FROM `board` WHERE `board`.`id_board` = ".$board;
    
            $res = @mysqli_query($conn, $sql);

            mysqli_close($conn);
        }
    }
    header('Location: ../../login/index.php');
}
?>