<?php
session_start(['cookie_lifetime' => '1728000', 'name' => 'shortener', 'cookie_httponly' => true,'cookie_secure' => true]);
$username = $_SESSION['username'];
if (isset($_GET['UNKNOWN']) and ($_SESSION['admin'] == '1')) {
    $username = 'UNKNOWN';
}
include("bdd.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shortener</title>
    <meta charset="utf-8"/>
</head>
<body><a href=".">Go Back !</a>

<?php
header("Cache-Control: no-cache, must-revalidate");
$root_url = $_SERVER['REQUEST_URI'];

if (empty($_SESSION['username'])) {
        header('Location: ' . DEFAULT_URL);
    exit();
}

if (isset($_GET['delete']) && $_GET['delete'] != "") {
    $req = $connexion->prepare('DELETE FROM shortener WHERE username= ? AND short = ?');
    $req->execute(array($username, $_GET['delete']));
    $req->closeCursor();
}
elseif (!empty($_GET['deleteRange']) ){
    $date = new DateTime("UTC");
    $date->modify('-'.$_GET['deleteRange'].' day');
    $date = $date->format('Y-m-d H:i:s');
    echo $date;
    if (!empty($_GET['keepBM']) && $_GET['keepBM'] == "true") {
        $req = $connexion->prepare('DELETE FROM shortener WHERE username= ? AND date < ? and comment is NULL');
        $req->execute(array($username, $date));
    }
    else {
        $req = $connexion->prepare('DELETE FROM shortener WHERE username= ? AND date < ?');
        $req->execute(array($username, $date));
    }
    $req->closeCursor();
}
echo '
    <table>
        <tr>
            <th>Short link</th>
            <th class="center-div" style="width: 500px;">Original link</th>
            <th>Total views</th>
        </tr>';
        
$list = $connexion->prepare('SELECT * FROM shortener WHERE username= ? ORDER BY date DESC;');
$list->execute(array($username));

while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td><a href=\"./" . $row['short'] . "\" >" . $row['short'] . "</a></td>";
    echo "<td><div class=\"comment\">" . $row['comment'] . "</div><a href=\"./" . $row['short'] . "\" >" . $row['url'] . "</a></td>";
    if ($username = 'UNKNOWN') {
        echo "<td>" . $row['views'] . "<a href=./list.php?UNKNOWN&delete=" . $row['short'] . " class=\"delete\" ><img src=\"/assets/img/delete-icon.png\" /></td></tr>";
    }
    else {
        echo "<td>" . $row['views'] . "<a href=./list.php?delete=" . $row['short'] . " class=\"delete\" ><img src=\"/assets/img/delete-icon.png\" /></td></tr>";
    }
}
$list->closeCursor();
if ($username = 'UNKNOWN') {
    $action = 'list.php?UNKNOWN';
}
else {
    $action='list.php';
}

echo '</table>
    <form action="'. $action .'" method="get" id="formDelete" >
        <label>Remove links older than
        <input type="number" name="deleteRange" value="30" />days</label><br />
        <label>keep bookmarks :<input type="checkbox" name="keepBM" value="true" /> </label>
        <input type="submit" value="Delete" />
    </form>
    <a href="' . DEFAULT_URL . '/list.php?UNKNOWN">Get link from no connected people</a>';
?>
</body>
