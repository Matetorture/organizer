<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.id_board, board.name, board.bg, board.date, userboard.owner FROM board, userboard WHERE userboard.id_board = board.id_board and userboard.id_user='".$_SESSION['id_user']."' ORDER BY board.date";

$res = @mysqli_query($conn, $sql);

?>
<div id="board-list-list">
    <br>

    <?php while ($row = $res->fetch_assoc()){ ?>
        <div class="board-list-board">
            <form class="list-board-name" action="index.php" method="get">
                <button type="submit" name="board" value="<?php echo $row['id_board']; ?>" style="border: #<?php echo $row['bg']; ?> solid 2px;" ><span class="list-board-name-name"><?php echo $row['name'] ?></span><?php echo "  -  ".$row['date']; ?></button>
            </form>
    
            <?php if($row['owner']){ ?>
    
                <button class="edit-button"><img src="src/svg/edit.svg" alt="edit"></button>
                <?php
                echo '<meta name="id" content="'.$row['id_board'].'">';
                ?>
                <script src="src/js/board_list.js"></script>
                
                <form action="handlers/board/delete.php" method="get">
                    <button type="submit" name="board" value="<?php echo $row['id_board']; ?>" class="delete-board"><img src="src/svg/delete.svg" alt="delete"></button>
                </form>
                
            <?php } ?>
    
            <br>
            <br>
        </div>

    <?php } ?>

    <br>
    <br>
    <form action="handlers/board/add.php" method="get">
        <input type="text" name="name" id="name" placeholder="board name" required minlength="4" maxlength="32" pattern="[a-zA-Z0-9]+">
        <button type="submit">Add board</button>
    </form>
</div>

<?php
mysqli_free_result($res);
mysqli_close($conn);

?>