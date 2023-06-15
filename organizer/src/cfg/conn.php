<?php

$name = "localhost";
$user = "root";
$pass = "";
$db = "b";

$conn = mysqli_connect($name, $user, $pass, $db);

$rezkod = @mysqli_query($conn, "SET NAME utf8");

?>