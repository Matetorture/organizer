<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}
if(!isset($_POST["elements"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

foreach($_POST['elements'] as $e){

    $sql = "UPDATE element SET text = '".$e['text']."', bg_color = '".$e['bgColor']."', text_color = '".$e['textColor']."', id_category=".$e['categoryId'].",  x='".$e['x']."', y='".$e['y']."' WHERE id_element = ".$e['id'].";";

    $res = @mysqli_query($conn, $sql);
}
foreach($_POST['categories'] as $e){

    $sql = "UPDATE category SET name = '".$e['name']."', color = '".$e['color']."' WHERE id_category = ".$e['id'].";";

    $res = @mysqli_query($conn, $sql);
}


mysqli_close($conn);
?>