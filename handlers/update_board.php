<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(!isset($_POST["elements"]) || !isset($_POST["categories"])){
        header('Location: ../login/index.php');
    }else{

        include '../src/cfg/conn.php';
        
        foreach($_POST['elements'] as $e){
        
            $sql = "UPDATE element SET text = '".$e['text']."', bg_color = '".$e['bgColor']."', text_color = '".$e['textColor']."', id_category=".$e['categoryId'].",  x='".$e['x']."', y='".$e['y']."' WHERE id_element = ".$e['id'].";";
        
            $res = @mysqli_query($conn, $sql);
        }
        foreach($_POST['categories'] as $e){
        
            $sql = "UPDATE category SET name = '".$e['name']."', color = '".$e['color']."' WHERE id_category = ".$e['id'].";";
        
            $res = @mysqli_query($conn, $sql);
        }

        mysqli_close($conn);
    }
}
?>