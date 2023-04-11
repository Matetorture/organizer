<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.name, board.date FROM board, userboard WHERE userboard.id_board = board.id_board and userboard.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

$row = $res->fetch_assoc()

?>

<p id="board-name"><?php echo $row['name']."  -  ".$row['date']; ?></p>

<?php

$sql = "SELECT element.id_element, element.text, element.bg_color, element.text_color, element.x, element.y, element.id_category FROM element WHERE element.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

$howManyElements = $res->num_rows;
echo '<meta name="howManyElements" content="'.$howManyElements.'">';

$i = 0;
while ($row = $res->fetch_assoc()){
    echo '<meta name="id'.$i.'" content="'.$row['id_element'].'">';
    echo '<meta name="text'.$i.'" content="'.$row['text'].'">';
    echo '<meta name="bgColor'.$i.'" content="'.$row['bg_color'].'">';
    echo '<meta name="textColor'.$i.'" content="'.$row['text_color'].'">';
    echo '<meta name="x'.$i.'" content="'.$row['x'].'">';
    echo '<meta name="y'.$i.'" content="'.$row['y'].'">';
    echo '<meta name="idc'.$i.'" content="'.$row['id_category'].'">';
    $i++;
}

mysqli_free_result($res);


$sql = "SELECT DISTINCT category.id_category, category.name, category.color FROM category WHERE category.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

$howManyCategories = $res->num_rows;
echo '<meta name="howManyCategories" content="'.$howManyCategories.'">';

$i = 0;
while ($row = $res->fetch_assoc()){
    echo '<meta name="cid'.$i.'" content="'.$row['id_category'].'">';
    echo '<meta name="cname'.$i.'" content="'.$row['name'].'">';
    echo '<meta name="ccolor'.$i.'" content="'.$row['color'].'">';
    $i++;
}

mysqli_free_result($res);

echo '<meta name="owner" content="'.$_SESSION['owner'].'">';
echo '<meta name="edit" content="'.$_SESSION['edit'].'">';
echo '<meta name="addusers" content="'.$_SESSION['add_users'].'">';
echo '<meta name="editusers" content="'.$_SESSION['edit_users'].'">';
echo '<meta name="kickusers" content="'.$_SESSION['kick_users'].'">';

$sql = "SELECT user.id_user, user.name, userboard.owner, userboard.edit, userboard.add_users, userboard.edit_users, userboard.kick_users FROM user, userboard WHERE user.id_user = userboard.id_user and userboard.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

?>
<div id="content">
    <canvas id="board"></canvas>
    <div id="left-panel"></div>
    <div id="users-list-bg"></div>
    <div id="users-list">
        <div id="close-user-list">X</div>
        <?php 
        $i = 0;
        echo '<ul id="users-list-list">';
        ?>
            <span class="users-list-users">users</span>
            <span class="users-list-permission">
                edit addU editU kickU
            </span>
        <?php
        while ($row = $res->fetch_assoc()){
            ?>
            <li>
                <span class="users-list-users"><?php echo $row['name'].$row['owner']; ?></span>
                <span class="users-list-permission">
                    <input type="checkbox" name="edit<?php echo $row['id_user'] ?>"
                    <?php
                    if($row['edit']){
                        echo ' checked ';
                    }
                    if($row['owner']){
                        echo ' disabled ';
                    }
                    ?>
                    >
                    <input type="checkbox" name="add-users<?php echo $row['id_user'] ?>"
                    <?php
                    if($row['add_users']){
                        echo ' checked ';
                    }
                    if($row['owner']){
                        echo ' disabled ';
                    }
                    ?>
                    >
                    <input type="checkbox" name="edit-users<?php echo $row['id_user'] ?>"
                    <?php
                    if($row['edit_users']){
                        echo ' checked ';
                    }
                    if($row['owner']){
                        echo ' disabled ';
                    }
                    ?>
                    >
                    <input type="checkbox" name="kick-users<?php echo $row['id_user'] ?>"
                    <?php
                    if($row['kick_users']){
                        echo ' checked';
                    }
                    if($row['owner']){
                        echo ' disabled ';
                    }
                    ?>
                    >
                    <?php 
                    if($_SESSION['kick_users']){
                    ?>
                    <form action="handlers/update_users.php" method="post">
                        <button type="submit" name="delete-user" value="<?php echo $row['id_user'] ?>">D</button>
                    </form>
                    <?php
                    }
                    ?>
                </span>
            </li>
            <?php
            $i++;
        }
        if($_SESSION['add_users']){
            echo '<button id="addUser">Add User</button>';
        }
        echo '</ul>';
        mysqli_free_result($res);
        mysqli_close($conn);
        ?>
    </div>
</div>