<?php
session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(!isset($_POST["elements"]) || !isset($_POST["categories"]) || !isset($_POST["boardBg"]) || !isset($_POST["which"]) || !isset($_POST["id"])){
        header('Location: ../login/index.php');
    }else{
        if(!preg_match ("/^[A-Z0-9]+/i", $_POST['which']) || !is_numeric($_POST["id"])){
            header('Location: ../login/index.php');
        }else{
            if($_POST['which'] == 'all'){

                $is_safely = true;
        
                if(!preg_match ("/^[A-Z0-9]+/i", $_POST['boardBg'])){
                    $is_safely = false;
                }
        
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
        
                    $sql = "UPDATE board SET bg = '".$_POST['boardBg']."' WHERE id_board = ".$_SESSION['board'].";";
                    
                    $res = @mysqli_query($conn, $sql);
                    
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
            elseif($_POST['which'] == 'category'){
                if(preg_match ("/^[A-Z0-9]+/i", $_POST['categories']['name']) && preg_match ("/^[A-Z0-9]+/i", $_POST['categories']['color']) && is_numeric($_POST['categories']['id']) && is_numeric($_POST['categories']['layer'])){

                    include '../src/cfg/conn.php';

                    $sql = "UPDATE category SET name = '".$_POST['categories']['name']."', color = '".$_POST['categories']['color']."', layer = '".$_POST['categories']['layer']."' WHERE id_category = ".$_POST['categories']['id'].";";
                    
                    $res = @mysqli_query($conn, $sql);
                    mysqli_close($conn);
                }
            }elseif($_POST['which'] == 'element'){
                if(preg_match ("/^[A-Z0-9]+/i", $_POST['elements']['text']) && preg_match ("/^[A-Z0-9]+/i", $_POST['elements']['bgColor']) && preg_match ("/^[A-Z0-9]+/i", $_POST['elements']['textColor']) && is_numeric($_POST['elements']['categoryId']) && is_numeric($_POST['elements']['x']) && is_numeric($_POST['elements']['y']) && is_numeric($_POST['elements']['id']) && is_numeric($_POST['elements']['layer'])){

                    include '../src/cfg/conn.php';

                    $sql = "UPDATE element SET text = '".$_POST['elements']['text']."', bg_color = '".$_POST['elements']['bgColor']."', text_color = '".$_POST['elements']['textColor']."', id_category=".$_POST['elements']['categoryId'].",  x='".$_POST['elements']['x']."', y='".$_POST['elements']['y']."', layer = '".$_POST['elements']['layer']."' WHERE id_element = ".$_POST['elements']['id'].";";
                    
                    $res = @mysqli_query($conn, $sql);
                    mysqli_close($conn);
                }
            }else{
                echo 'Error: $_POST["which"]';
            }
        }


    }
}
?>