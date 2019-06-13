<?php
include("bdd.php");

if (isset($_GET['site']) && !empty($_GET['site'])) { //url shortened
    /*  BDD plan
        TABLE : shortener
        short | url | comment | views | username | date
    */

    $site = $_GET['site'];

    $get_site = $connexion->prepare('SELECT url, short, views FROM shortener WHERE short=?');
    $get_site->execute(array($site));
    $res_site = $get_site->fetch(PDO::FETCH_ASSOC);

    if ($res_site) //if it exists
    {
        $views_plus_1 = $res_site['views'] + 1;

        $query_update = $connexion->prepare('UPDATE shortener SET views=? WHERE short=?');
        $query_update->execute(array($views_plus_1, $res_site['short']));

        header('Location: ' . $res_site['url']);
    } else {
        header('Location: ' . DEFAULT_URL);
    }
    exit();
}

session_start(['cookie_lifetime' => '1728000', 'name' => 'shortener', 'cookie_httponly' => true, 'cookie_secure' => true]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shortener</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="/assets/css/spectre.min.css"/>
</head>
<body>

<?php
$username = $_SESSION['username'];

if (empty($username)) { // Si l'utilisateur n'est pas connecté
    echo '
    <div class="form login">
    <form action="login.php" method="POST" id="login">
      <input type="text" name="username" placeholder="UserName" autofocus />
      <input type="password" name="password" placeholder="Password" />
      <input type="submit" value="login" />
    </form></div>';
    if (ALLOW_SIGNIN == 'true'){
        echo '
        <div class="form signin">
        <form action="login.php?signin" method="POST" id="signin">
          <input type="text" name="username" placeholder="UserName" />
          <input type="password" name="password" placeholder="Password" />
          <input type="email" name="email" placeholder="Email" />
          <input type="submit" value="SignIn" />
        </form></div>';
    }
}
else {
    echo '<h3>Connected as ' . $username . '</h3>';
}

if (PUBLIC_INSTANCE == 'true'){
	$username = 'UNKNOWN';
}

// Main page

$code_js = 'javascript:(function () {var d = document;var w = window;var enc = encodeURIComponent;var f =\' ' . DEFAULT_URL . '\';var l = d.location;var p = \'/shorten.php?url=\' + enc(l.href) + \'&amp;comment=\' + enc(d.title) + \'&amp;token=' . $_SESSION['token'] . '\';var u = f + p;var a = function () {if (!w.open(u))l.href = u;};if (/Firefox/.test(navigator.userAgent))setTimeout(a, 0); else a();void(0);})()';
?>
    <a class="forkit" href="https://github.com/azlux/Simple-URL-Shortener/">
        <span>Fork me on GitHub!</span>
        <span>Get free cookie!</span>
    </a>
    <div id="content">
        <div id="form">
            <form name="url_form" action="shorten.php" method="post">
                <input type="text" name="url" autocomplete="off" placeholder="Link to shorten" />
                <input type="text" name="comment" id="comment" maxlength="30" autocomplete="off" placeholder="Optional comment" style="width: 160px;"/>
                <input type="submit" value="Shorten" class="button"/>
            </form>
        </div>
        <a id="username" href="/list.php" >List of shortened links</a>
	<a id="bookmark" href="<?php echo $code_js ?>" onclick="event.preventDefault();"/>Shortcut</a>
        
        <div id="info_shortcut" onclick="document.getElementById(\'instructions\').style.display = \'block\';">i</div>
        <div id="credits">
            Shortener by Azlux
        </div>
    </div>
    <div id="instructions">You can add this link as bookmark (click and drop into your bookmark toolbar). After that, you can click on the bookmark to add the current url page directly into this shortener.<h3>Enjoy the feature !</h3></div>
    <a href="<?php echo DEFAULT_URL?>/login.php?logout">Logout</a>
</body>
</html>
