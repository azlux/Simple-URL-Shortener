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
    exit;
}
elseif (!empty($_POST['username']) AND !empty($_POST['password'])) {
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
    exit;
}

// Authenticated part

if(!empty($_SESSION['username'])) {
    if (isset($_GET['changepassword']) AND !empty($_POST['old_password']) AND !empty($_POST['new_password'])) {
        $req = $connexion->prepare('UPDATE users SET password = ? WHERE username = ? AND password = ?');
        $req->execute(array($_POST['new_password'], $_SESSION['username'], $_POST['old_password']));
        echo "PASSWORD UPDATED";
        header("refresh:5;url=" . DEFAULT_URL);
    }
}
header('Location: ' . DEFAULT_URL);
