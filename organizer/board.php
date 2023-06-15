<a href="index.php" id="list-link">ORGANIZER LIST</a>
<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.name, board.bg, board.date FROM board, userboard WHERE userboard.id_board = board.id_board and userboard.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

$row = $res->fetch_assoc();
$boardBg = $row['bg'];

?>

<span id="board-name"><?php echo $row['name']."  -  ".$row['date']; ?></span>

<?php

$sql = "SELECT element.id_element, element.text, element.bg_color, element.text_color, element.x, element.y, element.id_category, element.layer FROM element, category WHERE element.id_board='".$_SESSION['board']."' and element.id_category = category.id_category ORDER BY category.layer DESC, category.name, element.layer DESC, element.text";

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
    echo '<meta name="layer'.$i.'" content="'.$row['layer'].'">';
    $i++;
}

mysqli_free_result($res);


$sql = "SELECT DISTINCT category.id_category, category.name, category.color, category.layer FROM category WHERE category.id_board='".$_SESSION['board']."' ORDER BY category.layer DESC, category.name";

$res = @mysqli_query($conn, $sql);

$howManyCategories = $res->num_rows;
echo '<meta name="howManyCategories" content="'.$howManyCategories.'">';

$i = 0;
while ($row = $res->fetch_assoc()){
    echo '<meta name="cid'.$i.'" content="'.$row['id_category'].'">';
    echo '<meta name="cname'.$i.'" content="'.$row['name'].'">';
    echo '<meta name="ccolor'.$i.'" content="'.$row['color'].'">';
    echo '<meta name="clayer'.$i.'" content="'.$row['layer'].'">';
    $i++;
}

mysqli_free_result($res);

echo '<meta name="owner" content="'.$_SESSION['owner'].'">';
echo '<meta name="edit" content="'.$_SESSION['edit'].'">';
echo '<meta name="addusers" content="'.$_SESSION['add_users'].'">';
echo '<meta name="editusers" content="'.$_SESSION['edit_users'].'">';
echo '<meta name="kickusers" content="'.$_SESSION['kick_users'].'">';

$sql = "SELECT user.id_user, user.name, userboard.owner, userboard.edit, userboard.add_users, userboard.edit_users, userboard.kick_users FROM user, userboard WHERE user.id_user = userboard.id_user and userboard.id_board='".$_SESSION['board']."' ORDER BY userboard.owner DESC, userboard.kick_users DESC, userboard.edit_users DESC, userboard.add_users DESC, userboard.edit DESC,  user.name";

$res = @mysqli_query($conn, $sql);

?>
<input type="color" name="boardBg" id="boardBg" value="#<?php echo $boardBg; ?>">

<?php
if(true){
?>

<img src="src/svg/save.svg" alt="save" id="save">

<?php
}
?>

<br><br>

<div id="content">
    <canvas id="board"></canvas>
    <div id="left-panel"></div>
    <div id="users-list-bg"></div>
    <div id="users-list">
        <div id="close-user-list"><img src="src/svg/close.svg" alt="close" width="100%" height="100%"></div>
        <?php 
        $i = 0;
        echo '<ul id="users-list-list">';
        ?>
            <span class="users-list-users">USERS</span>
            <span class="users-list-permission">
                <span class="permission-other-color">Edit board</span> Add user <span class="permission-other-color">Edit user</span> Kick user <span class="permission-other-color">Delete</span>
            </span>
        <?php
        while ($row = $res->fetch_assoc()){
            ?>
            <li>
                <span class="users-list-users"
                <?php
                if($row['owner']){
                    echo ' style="color: var(--primary-color);" ';
                }
                ?>
                ><?php echo $row['name'] ?></span>
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
                        <button type="submit" name="delete-user" value="<?php echo $row['id_user'] ?>" style="border: none; padding: 0; margin: 0;"><img src="src/svg/delete.svg" alt="delete" width="24px" height="24px"></button>
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
            echo '<br><form action="handlers/update_users.php" method="post"><input type="text" name="name" placeholder="user name"><button id="addUser"  name="add-user">Add User</button></form>';
        }
        echo '</ul>';
        mysqli_free_result($res);
        mysqli_close($conn);
        ?>
    </div>
</div>