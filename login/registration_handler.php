<?php
session_start();

if(!isset($_POST['login']) || !isset($_POST['pass']) || !isset($_POST['name'])){
    header('Location: index.php');
}else{
    if(!preg_match ("/^[A-Z0-9]+/i", $_POST['login']) || !preg_match ("/^[A-Z0-9]+/i", $_POST['pass']) || !preg_match ("/^[A-Z0-9]+/i", $_POST['name']) || strlen($_POST['login']) < 8 || strlen($_POST['login']) > 32 || strlen($_POST['pass']) < 8 || strlen($_POST['pass']) > 32 || strlen($_POST['name']) < 8 || strlen($_POST['name']) > 32 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        header('Location: index.php');
    }else{

        include '../src/cfg/conn.php';
        
        $sql = "INSERT INTO `user` (`id_user`, `name`, `email`, `login`, `pass`) VALUES (NULL, '".$_POST['name']."', '".$_POST['email']."', '".$_POST['login']."', '".$_POST['pass']."');";
        
        $res = @mysqli_query($conn, $sql);
        
        mysqli_close($conn);
        
        header('Location: index.php');
    }
    
}
?>