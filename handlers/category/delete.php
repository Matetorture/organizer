<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../../login/index.php');
}else{
    if(!$_SESSION['edit']){
        header('Location: ../../index.php?board='.$_SESSION['board']);
    }else{

        include '../../src/cfg/conn.php';

        $sql = "DELETE FROM `category` WHERE `category`.`id_category` = ".$_GET['id'];

        $res = @mysqli_query($conn, $sql);

        mysqli_close($conn);

        header('Location: ../../index.php?board='.$_SESSION['board']);
    }
}
?>