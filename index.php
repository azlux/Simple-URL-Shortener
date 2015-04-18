<!DOCTYPE html>
<html>
<head>
    <title>Shortener</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="commun.css"/>
</head>

<body><?php
$root_url = $_SERVER['REQUEST_URI'];
include("bdd.php");

if (isset($_GET['site']) && $_GET['site'] != "") //url shortened
{
    /*  BDD plan
        TABLE : shortener
        id | short | url | comment | views | id_user | date
    */

    $site = $_GET['site'];

    $req_site_exists = "SELECT count(*) FROM shortener WHERE short='" . $site . "';"; //does the site exist

    $res_site_exists = $connexion->query($req_site_exists);
    $res_site_exists->setFetchMode(PDO::FETCH_OBJ);
    $res_site_exists = $res_site_exists->fetchColumn();

    if ($res_site_exists > 0) //if it exists
    {
        $get_site = $connexion->prepare('SELECT url, id, views FROM shortener WHERE short=?');
        $get_site->execute(array($site));
        $res_site = $get_site->fetch(PDO::FETCH_ASSOC);

        $views_plus_1 = $res_site['views'] + 1;

        $query_update = $connexion->prepare('UPDATE shortener SET views=? WHERE id=?');
        $query_update->execute(array($views_plus_1, $res_site['id']));

        header('Location: ' . $res_site['url']);
    } else {
        header('Location: /');
    }
} else if (isset($_GET['shorten']) && $_GET['shorten'] != "") {
    $shorten = $_GET['shorten'];
    if (preg_match("_(^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|'()\[\]%#,?]+[\w/#](\(\))?)(?=$|[\s',\|\(\).:;?\-\[\]>\)])_i", $shorten)) {
        $unic = 0;
        while ($unic == 0) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $url_shortened = '';
            for ($i = 0; $i < 4; $i++) {
                $url_shortened .= $characters[rand(0, strlen($characters) - 1)];
            }

            $req_verify_url = "SELECT count(*) FROM shortener WHERE short='" . $url_shortened . "';"; //select the post

            $verify_url = $connexion->query($req_verify_url);
            $verify_url->setFetchMode(PDO::FETCH_OBJ);
            $verify_url = $verify_url->fetchColumn();

            if ($verify_url == 0) {
                $unic = 1;
            }
        }
        $userID = $_GET['userID'];
        if (isset($_GET['comment']) && $_GET['comment'] != "") {
            $req = $connexion->prepare('INSERT INTO shortener(short,url,comment,id_user,date,views) VALUES (?,?,?,?,?,?)');
            $req->execute(array($url_shortened, $shorten, $_GET['comment'], $userID, date("Y-m-d"), '0'));

        } else {
            $req = $connexion->prepare('INSERT INTO shortener(short,url,id_user,date,views) VALUES (?,?,?,?,?)');
            $req->execute(array($url_shortened, $shorten, $userID, date("Y-m-d"), '0'));
        }
        $req->closeCursor();

        echo '
				<div id="content">
					<div id="site">
						<a href=".">Shortener</a>
					</div>

					<div id="shortened">
						URL shortened : <a href="' . $url_shortened . '">' . $root_url . "/" . $url_shortened . '</a>
					</div>

					<div id="credits">
						Shortener by Azlux
					</div>
				</div>';
    } else {
        echo "Wrong URL";
    }

} else //need to short
{
    echo '
		<div id="content">
			<div id="form">
				<form name="url_form" action="index.php" method="get">
					<input type="text" name="shorten" autocomplete="off" placeholder="Link to shorten" />
					<input type="text" name="comment" id="comment" maxlength="30" autocomplete="off" placeholder="Optional comment" style="width: 160px;"/>
					<input type="hidden" name="userID" value="" id="userID2" />
					<input type="submit" value="Shorten" class="button"/>
				</form>
			</div>
			<a id="userID" href="" >List of shortened links</a>
			<div id="credits">
				Shortener by Azlux
			</div>
		</div>';
}

?>
<script src="cookie.js"></script>
</body>
</html>
