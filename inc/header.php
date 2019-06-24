<!DOCTYPE html>
<html>
<head>
    <title>Shortener</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="assets/css/spectre-<?php echo WEB_THEME ?>.css"/>
    <link rel="stylesheet" href="assets/css/icons.min.css"/>
    <link rel="stylesheet" href="assets/css/common.css"/>
</head>
<body class="<?php echo WEB_THEME; ?>">
<div id="banner" class="flex flex-space">
    <?php
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $token = $_SESSION['token'];
    ?>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" tabindex="0">Connected as <?php echo $username ?>
            <i class="icon icon-caret"></i>
        </button>
        <ul class="menu">
        <li class="menu-item">
            <button class="btn" onClick="openModal('modal-change-pwd')">Change Password</a>
        </li>
            <li class="menu-item">
            <button class="btn" onclick="window.location.href = 'login.php?logout'">Logout</button>
            </li>
        </ul>
    </div>

    <?php
        }
        else{
    ?>
        <button class="btn" onClick="openModal('modal-login')">Login</button>
        <?php
            if (ALLOW_SIGNIN == 'true'){
        ?>
            <button class="btn" onClick="openModal('modal-signin')">Sign In</button>
        <?php
            }
        }
    ?>
</div>
<script>
    function closeAllModals(){
        document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'))
    }

    function openModal(modalId){
        document.getElementById(modalId).classList.add('active')
    }
</script>
<div class="modal modal-sm" id="modal-login">
  <a class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
      <div class="modal-title h5">Login Form</div>
    </div>
    <div class="modal-body">
      <div class="content">
        <form action="login.php" method="POST" id="login">
        <div class="form-group">
            <label class="form-label" for="login_username">UserName</label>
            <input class="form-input" type="text" id="login_username" name="username"/>
        </div>
        <div class="form-group">
            <label class="form-label" for="login_password">Password</label>
            <input class="form-input" type="password" id="login_password" name="password"/>
        </div>
        <input class="btn float-right" type="submit" value="Login" />
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal modal-sm" id="modal-signin">
  <a class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
      <div class="modal-title h5">Register Form</div>
    </div>
    <div class="modal-body">
      <div class="content">
        <form action="login.php?signin" method="POST" id="signin">
        <div class="form-group">
            <label class="form-label" for="register_username">UserName</label>
            <input class="form-input" type="text" id="register_username" name="username"/>
        </div>
        <div class="form-group">
            <label class="form-label" for="register_password">Password</label>
            <input class="form-input" type="text" id="register_password" name="username"/>
        </div>
        <div class="form-group">
            <label class="form-label" for="register_email">Email</label>
            <input class="form-input" type="email" id="register_email" name="email"/>
        </div>
        <input class="btn float-right" type="submit" value="SignIn" />
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal modal-sm" id="modal-change-pwd">
  <a class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
      <div class="modal-title h5">Change Password Form</div>
    </div>
    <div class="modal-body">
      <div class="content">
        <form action="login.php?changepassword" method="POST" id="login">
        <div class="form-group">
            <label class="form-label" for="old_password">Old Password</label>
            <input class="form-input" type="password" id="old_password" name="old_password"/>
        </div>
                <div class="form-group">
            <label class="form-label" for="new_password">New Password</label>
            <input class="form-input" type="password" id="new_password" name="new_password"/>
        </div>
        <input class="btn float-right" type="submit" value="Login" />
        </form>
      </div>
    </div>
  </div>
</div>
<script>
	function closeAllModals(){
        document.querySelectorAll('.modal').forEach(m => m.classList.remove('active'))
    }
    function openModal(modalId){
        document.getElementById(modalId).classList.add('active')
    }
	[
		...document.querySelectorAll('.modal-overlay'),
		...document.querySelectorAll('.modal .btn-clear')
	].forEach(o => o.addEventListener('click', closeAllModals))
</script>

