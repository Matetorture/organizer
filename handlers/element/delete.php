<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include '../../src/cfg/conn.php';

$sql = "DELETE FROM `element` WHERE `element`.`id_element` = ".$_GET['id'];

$res = @mysqli_query($conn, $sql);

mysqli_close($conn);

header('Location: ../../index.php?board='.$_SESSION['board']);
?>