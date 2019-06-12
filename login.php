<?php

include("bdd.php");
session_start(['cookie_lifetime' => 1728000,'name' => 'shortener', 'cookie_httponly' => true,'cookie_secure' => true]);

if (isset($_GET['logout'])) {
    session_destroy();
}
if (isset($_GET['signin']) and !empty($_POST['username']) and !empty($_POST['password']) and !empty($_POST['email'])) {
    if ($_POST['username'] == 'UNKNOWN') {
	    echo 'Username not allowed';
    }
    else {
        $options = ['cost' => 12,];
        $pwd_hash = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
        $is_admin=0;
        $res = $connexion->query('SELECT count(*) FROM users');
        if ($res->fetch()[0] == 0) {
	    $is_admin=1;
        }
        $req = $connexion->prepare('INSERT INTO users (username, password, email, token, admin) VALUES (?,?,?,?,?)');
        $req->execute(array($_POST['username'], $pwd_hash, $_POST['email'], uniqid(), $is_admin));
        echo 'ACCOUNT CREATED.';
    }
    header("refresh:5;url=" . DEFAULT_URL);
}


if (empty($_POST['username']) OR empty($_POST['password'])) {
    header('Location: ' . DEFAULT_URL);
    exit();
}


$req = $connexion->prepare('SELECT * FROM users where username=?');
$req->execute([$_POST['username']]);
$user = $req->fetch();

if ($user && password_verify($_POST['password'], $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['token'] = $user['token'];
    $_SESSION['admin'] = $user['admin'];
    
    header('Location: ' . DEFAULT_URL);
}
else {
    http_response_code(403);
}
