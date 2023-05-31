<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../login/index.php');
}else{
    if(isset($_POST['delete-user'])){

        if(is_numeric($_POST['delete-user'])){

            include '../src/cfg/conn.php';

            $sql = "DELETE FROM `userboard` WHERE `userboard`.`id_user` = ".$_POST['delete-user']." and `userboard`.`owner` = 0 ";
        
            $res = @mysqli_query($conn, $sql);

            mysqli_close($conn);
        }
        header('Location: ../index.php?board='.$_SESSION['board']);
    }else{
        if(isset($_POST['add-user']) && isset($_POST['name'])){

            if(preg_match("/^[A-Z0-9]+/i", $_POST['name'])){
                include '../src/cfg/conn.php';
            
                $sql = "SELECT id_user FROM user WHERE name='".$_POST['name']."';";

                $res = @mysqli_query($conn, $sql);

                $users = $res->num_rows;

                if($users>0){
 
                    $row = $res->fetch_assoc();
    
                    mysqli_free_result($res);
    
                    $sql = "SELECT id_userboard FROM userboard WHERE id_user='".$row['id_user']."' and id_board='".$_SESSION['board']."';";
                    
                    $res = @mysqli_query($conn, $sql);

                    $is_exist = $res->num_rows;

                    mysqli_free_result($res);
    
                    if($is_exist==0){
        
                        $sql = "INSERT INTO `userboard` (`id_userboard`, `id_board`, `id_user`, `owner`, `edit`, `add_users`, `edit_users`, `kick_users`) VALUES (NULL, '".$_SESSION['board']."', '".$row['id_user']."', '0', '0', '0', '0', '0');";
    
                        $res = @mysqli_query($conn, $sql);
                    }
        
                    mysqli_close($conn);
                }
            }
            header('Location: ../index.php?board='.$_SESSION['board']);

        }else{
            if(!isset($_POST["users"])){
                header('Location: ../login/index.php');
            }else{

                if(is_numeric($_POST['users']['edit']) && is_numeric($_POST['users']['addUsers']) && is_numeric($_POST['users']['editUsers']) && is_numeric($_POST['users']['kickUsers']) && is_numeric($_POST['users']['id'])){
    
                    include '../src/cfg/conn.php';
            
                    $sql = "UPDATE userboard SET edit = '".$_POST['users']['edit']."', add_users = '".$_POST['users']['addUsers']."', edit_users = '".$_POST['users']['editUsers']."', kick_users = '".$_POST['users']['kickUsers']."' WHERE id_user = ".$_POST['users']['id']." and owner = 0;";
                
                    $res = @mysqli_query($conn, $sql);
            
                    mysqli_close($conn);
                }
            }
        }
    }
}
?>