<?php
if (isset($_GET['type']) and ($_GET['type'] == 'sqlite3' or $_GET['type'] == 'mysql')) {
    if ($_GET['type'] == 'sqlite3') {
        $connexion = new PDO('sqlite:./database.sqlite3');
        file_put_contents("./bdd.php", '<?php
try {
    $connexion = new PDO("sqlite:./database.sqlite3;charset=utf8");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}');
    }
    elseif ($_GET['type'] == 'mysql') {
        if (isset($_POST['username']) AND !empty($_POST['username']) AND !empty($_POST['pwd'])) {
            $connexion = new PDO("mysql:host=$hostname;dbname=URLShortener", $_POST['username'], $_POST['pwd']);
            file_put_contents("./bdd.php", '<?php
$hostname = \'localhost\';
$username = \'' . $_POST['username'] . '\';
$password = \'' . $_POST['pwd'] . '\';
try {
$connexion = new PDO("mysql:host=$hostname;dbname=URLShortener;charset=utf8", $username, $password);
$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
echo $e->getMessage();
}');
        } else {
            header('Location: installation.php?form');
        }
    }
    if ($connexion->errorCode() == 0) {
        $connexion->query('CREATE TABLE shortener(
                        short CHAR(5) PRIMARY KEY NOT NULL,
                        url VARCHAR(700) NOT NULL,
                        comment CHAR(30),
                        views INT,
                        id_user CHAR(4),
                        date DATETIME NOT NULL
                         );
                        CREATE INDEX id_user ON shortener (id_user);');
        header('Location: installation.php?ok');
    } else {
        echo "SQL ERROR   ".$connexion->errorCode();
    }
} elseif
(isset($_GET['ok'])) {
    echo "<h3>The installation is finish. You need to delete the file  \"installation.php\"  now !!! for safety (check if it work before that :)</h3>";
} elseif (isset($_GET['form'])) {
    echo '
                <form id="formulaire" action="installation.php?type=mysql" method="post">Username and password of the MySQL database.<br /> the new database need to be named "URLShortener"<br />
                        <input type="text" name="username" placeholder="Username" />
                        <input type="password" name="pwd" placeholder="Password" />
                        <input type="submit" value="Finish the install" />
                    </form>
		';
} else {
    echo 'Choose your kind of database :   <a href="./installation.php?type=sqlite3" >SQLite3</a> <a href="./installation.php?form" > MySQL</a>';
}
?>
