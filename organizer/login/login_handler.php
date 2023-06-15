<?php
session_start();
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: index.php');
}else{
    if(!isset($_POST['login']) || !isset($_POST['pass'])){
        header('Location: index.php');
    }else{
        if(!preg_match ("/^[A-Z0-9]+/i", $_POST['login']) || !preg_match ("/^[A-Z0-9]+/i", $_POST['pass'])){
            header('Location: index.php');
        }else{

            include '../src/cfg/conn.php';
            
            $sql = "SELECT id_user, login, name, pass FROM user WHERE login='".$_POST['login']."' or name='".$_POST['login']."'";
            
            $res = @mysqli_query($conn, $sql);
            
            $users = $res->num_rows;
            
            if($users>0){
                $row = $res->fetch_assoc(); 
                if(password_verify($_POST['pass'], $row['pass'])){
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['id_user'] = $row['id_user'];
                }
            }
            
            mysqli_free_result($res);
            mysqli_close($conn);
            
            header('Location: index.php?msg=Wrong login/password');
        }
        
    }
}
?>