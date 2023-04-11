<?php
session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(!isset($_POST["elements"]) || !isset($_POST["categories"])){
        header('Location: ../login/index.php');
    }else{

        $is_safely = true;

        foreach($_POST['elements'] as $e){
            if(!preg_match ("/^[A-Z0-9]+/i", $e['text']) || !preg_match ("/^[A-Z0-9]+/i", $e['bgColor']) || !preg_match ("/^[A-Z0-9]+/i", $e['textColor']) || !is_numeric($e['categoryId']) || !is_numeric($e['x']) || !is_numeric($e['y']) || !is_numeric($e['id']) || !is_numeric($e['layer'])){
                $is_safely = false;
            }
        }
        foreach($_POST['categories'] as $e){
            if(!preg_match ("/^[A-Z0-9]+/i", $e['name']) || !preg_match ("/^[A-Z0-9]+/i", $e['color']) || !is_numeric($e['id']) || !is_numeric($e['layer'])){
                $is_safely = false;
            }
        }

        if($is_safely){
            
            include '../src/cfg/conn.php';
            
            foreach($_POST['elements'] as $e){
            
                $sql = "UPDATE element SET text = '".$e['text']."', bg_color = '".$e['bgColor']."', text_color = '".$e['textColor']."', id_category=".$e['categoryId'].",  x='".$e['x']."', y='".$e['y']."', layer = '".$e['layer']."' WHERE id_element = ".$e['id'].";";
            
                $res = @mysqli_query($conn, $sql);
            }
            foreach($_POST['categories'] as $e){
            
                $sql = "UPDATE category SET name = '".$e['name']."', color = '".$e['color']."', layer = '".$e['layer']."' WHERE id_category = ".$e['id'].";";
            
                $res = @mysqli_query($conn, $sql);
            }
    
            mysqli_close($conn);
        }

    }
}
?>