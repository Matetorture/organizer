<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: ../../login/index.php');
}else{
    if(!$_SESSION['edit']){
        header('Location: ../../index.php?board='.$_SESSION['board']);
    }else{

        include '../../src/cfg/conn.php';

        $sql = "INSERT INTO `element` (`id_element`, `id_board`, `id_user`, `text`, `bg_color`, `text_color`, `id_category`, `x`, `y`) VALUES (NULL, '".$_SESSION['board']."', '".$_SESSION['id_user']."', 'text', '000000', 'ffffff', '".$_GET['id']."', '0', '0');";

        $res = @mysqli_query($conn, $sql);

        mysqli_close($conn);

        header('Location: ../../index.php?board='.$_SESSION['board']);
    }
}
?>