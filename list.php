<?php
session_start(['cookie_lifetime' => '1728000', 'name' => 'shortener', 'cookie_httponly' => true,'cookie_secure' => true]);
if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
}
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
    <link rel="stylesheet" href="assets/css/spectre-<?php echo WEB_THEME ?>.css"/>
    <link rel="stylesheet" href="assets/css/common.css"/>
</head>
<body class="<?php echo WEB_THEME; ?>">

    <!-- je vais le mettre dans le header de la page avec login/logout/signin-->
    <button class="btn" onclick="window.location.href = '.'">Go Back !</button>
    <button class="btn" onclick="window.location.href = 'list.php?UNKNOWN'">Get link from no connected people</button>

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
    ?>
    <table id="list" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Short link</th>
                <th>Original link</th>
                <th>Total views</th>
                <th></th>
            </tr>
        </thead>
    <tbody>
    <?php

    $list = $connexion->prepare('SELECT * FROM shortener WHERE username= ? ORDER BY date DESC;');
    $list->execute(array($username));

    while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
        $short = $row['short'];
        $views = $row['views'];
        $comment = $row['comment'];
        $url = $row['url'];

        $linkUrl = sprintf("./%s", $short);
        $deleteUrl = sprintf("list.php?%sdelete=%s", $username == 'UNKNOWN' ? "UNKNOWN&" : "", $short);
?>
    <tr>
        <td>
            <a href="<?php echo $linkUrl ?>">
                <?php echo $short; ?>
            </a>
        </td>
        <td>
            <div><?php echo $comment; ?></div>
            <a href="<?php echo $linkUrl; ?>">
                <?php echo $url; ?>
            </a>
        </td>
        <td>
            <?php echo $views; ?>
        </td>
        <td>
            <a href="<?php echo $deleteUrl; ?>" class="delete">
                <img src="/assets/img/delete-icon.png"/>
            </a>
        </td>
    </tr>
<?php
}
$list->closeCursor();
if ($username = 'UNKNOWN') {
    $action = 'list.php?UNKNOWN';
}
else {
    $action='list.php';
}
?>

    </tbody>
    </table>
    <div id="content" class="form action"> 
        <form class="form" action="<?php echo $action; ?>" method="get" id="formDelete" >
            <div class="form-group">
                <label class="form-label" for="deleteRange">Remove links older than</label>
                <div class="input-group">
                    <input class="form-input" type="number" id="deleteRange" name="deleteRange" value="30"/>
                    <span class="input-group-addon">days</span>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="keepBM" value="true">
                    <i class="form-icon"></i>keep bookmarks
                </label>
            </div>

            <input class="btn" type="submit" value="Delete" />
        </form>
    </div>
</body>
