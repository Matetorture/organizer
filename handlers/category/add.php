<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../../login/index.php');
}else{
    if(!$_SESSION['edit']){
        header('Location: ../../index.php?board='.$_SESSION['board']);
    }else{
        
        include '../../src/cfg/conn.php';
        
        $sql = "INSERT INTO `category` (`id_category`, `id_board`, `id_user`, `name`, `color`) VALUES (NULL, '".$_SESSION['board']."', '".$_SESSION['id_user']."', 'name', '000000')";
        
        $res = @mysqli_query($conn, $sql);
        
        mysqli_close($conn);
        
        header('Location: ../../index.php?board='.$_SESSION['board']);
    }
}
?>