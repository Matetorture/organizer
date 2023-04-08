<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.id_board, board.name, board.date, userboard.id_permission FROM board, userboard WHERE userboard.id_board = board.id_board and userboard.id_user='".$_SESSION['id_user']."'";

$res = @mysqli_query($conn, $sql);

?>

<form action="index.php" method="get">
    <?php while ($row = $res->fetch_assoc()){ ?>
        <button type="submit" name="board" value="<?php echo $row['id_board']; ?>"><?php echo $row['name']."  -  ".$row['date']; ?></button>
    <?php } ?>
</form>

<?php

mysqli_free_result($res);
mysqli_close($conn);

?>