<?php

session_start();
if(!isset($_SESSION["name"])){
    header('Location: login/index.php');
}

include 'src/cfg/conn.php';

$sql = "SELECT board.name, board.date, permission.name as pname FROM board, userboard, permission WHERE userboard.id_permission = permission.id_permission and userboard.id_board = board.id_board and userboard.id_board='".$_SESSION['board']."'";

$res = @mysqli_query($conn, $sql);

$row = $res->fetch_assoc()

?>

<p id="board-name"><?php echo $row['name']."  -  ".$row['date']." perm > ".$row['pname']; ?></p>

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
mysqli_close($conn);
?>
<div id="c">
    <canvas id="board"></canvas>
    <div id="elements-list"></div>
</div>