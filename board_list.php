<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.id_board, board.name, board.date FROM board, userboard WHERE userboard.id_board = board.id_board and userboard.id_user='".$_SESSION['id_user']."' ORDER BY board.date";

$res = @mysqli_query($conn, $sql);

?>

<form action="index.php" method="get">
    <?php while ($row = $res->fetch_assoc()){ ?>
        <button type="submit" name="board" value="<?php echo $row['id_board']; ?>"><?php echo $row['name']."  -  ".$row['date']; ?></button>
    <?php } ?>
</form>
<br>
<form action="handlers/board/add.php" method="get">
    <input type="text" name="name" id="name" placeholder="board name" required minlength="4" maxlength="32" pattern="[a-zA-Z0-9]+">
    <button type="submit">Add board</button>
</form>

<?php

mysqli_free_result($res);
mysqli_close($conn);

?>