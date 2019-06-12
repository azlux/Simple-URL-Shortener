<?php
include("bdd.php");

if (PUBLIC_INSTANCE == 'true'){
    $username = 'UNKNOWN';
}
session_start(['cookie_lifetime' => '1728000', 'name' => 'shortener', 'cookie_httponly' => true,'cookie_secure' => true]);
$username = $_SESSION['username'];


function short($connexion, $username, $url, $comment) {
    if (preg_match("_(^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|'()\[\]%#,?]+[\w/#](\(\))?)(?=$|[\s',\|\(\).:;?\-\[\]>\)])_i", $url)) {
        $unic = 0;
        while ($unic == 0) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $url_shortened = '';
            for ($i = 0; $i < URL_SIZE; $i++) { // Generate the short url
                $url_shortened .= $characters[rand(0, strlen($characters) - 1)];
            }

            # Check if already exist
            $verify_url = $connexion->prepare("SELECT * FROM shortener WHERE short=?");
            $verify_url->execute(array($url_shortened));
            
            if (count($verify_url->fetchAll((PDO::FETCH_ASSOC))) == 0) {
                $unic = 1;
            }
        }
        
        $req = $connexion->prepare('INSERT INTO shortener(short,url,comment,username,date,views) VALUES (?,?,?,?,?,?)');
        $req->execute(array($url_shortened, $url, $comment, $username, date("Y-m-d H:i:s"), '0'));

        $req->closeCursor();
        return $url_shortened;
    }
    else {
        echo 'Wrong URL';
        exit;
    }
}


if (!empty($_GET['url'])) { // GET if bookmark used or API (with token if connected)
    $url = $_GET['url'];
    
    if (empty($_GET['comment'])) {
        $comment = NULL;
    }
    else {
        $comment = $_GET['comment'];
    }

    if (!empty($_GET['token']) and (PUBLIC_INSTANCE != 'true')) { //If token post, search the user to allow the shorten in case of private server
        $req = $connexion->prepare('SELECT * FROM users where token = ?');
        $req->execute(array($_GET['token']));
        $res_user = $req->fetch(PDO::FETCH_ASSOC);
        if ($res_user and !empty($_GET['url'])) { //token is valid
           $url_shortened = short($connexion, $res_user['username'], $url, $comment);
        }
    }
    elseif (PUBLIC_INSTANCE == 'true') {
        $url_shortened = short($connexion, $username, $url, $comment);
    }
    else {
        header('Location: ' . DEFAULT_URL);
    }
}
else { // POST if webpage used
    if (empty($username)) { // Not connect and not a public shortener
        header('Location: ' . DEFAULT_URL);
        exit();
    }

    if (empty($_POST['url'])) { // nothing to short
        header('Location: ' . DEFAULT_URL);
        exit();
    }

    $url = $_POST['url'];
    if (empty($_POST['comment'])) {
        $comment = NULL;
    }
    else {
        $comment = $_GET['comment'];
    }
    $url_shortened = short($connexion, $username, $url, $comment);
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Shortener</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="/assets/css/commun.css"/>
    </head>
    <body>
<?php 
echo '
    <div id="content">
        <div id="site">
            <a href=".">Shortener</a>
        </div>

        <div id="shortened">
            URL shortened : <br /><a id="newURL" href="' . DEFAULT_URL . '/' . $url_shortened . '">' . DEFAULT_URL . '/' . $url_shortened . '</a>
        </div>

        <div id="credits">
            Shortener by Azlux
        </div>
        <script>
            window.prompt("Copy to clipboard: Ctrl+C, Enter","' . DEFAULT_URL . '/' . $url_shortened . '");
        </script>
    </div>';

?>
