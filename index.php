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
    <link rel="stylesheet" href="assets/css/spectre.min.css"/>
    <link rel="stylesheet" href="assets/css/common.css"/>
</head>
<body>
<div id="banner" class="flex flex-space">
    <?php
        if(!empty($_SESSION['username'])){
            $username = $_SESSION['username'];
            $token = $_SESSION['token'];
    ?>
        <h3>Connected as <?php echo $username ?></h3>
        <a class="btn" href="login.php?logout">Logout</a>
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
        <input class="btn float-right" class="btn float-right" type="submit" value="SignIn" />
        </form>
      </div>
    </div>
  </div>
</div>
<?php
if (PUBLIC_INSTANCE == 'true' and empty($username)){
	$username = 'UNKNOWN';
    $token = '';
}


// Main page
$code_js = 'javascript:(function () {var d = document;var w = window;var enc = encodeURIComponent;var f =\' ' . DEFAULT_URL . '\';var l = d.location;var p = \'/shorten.php?url=\' + enc(l.href) + \'&amp;comment=\' + enc(d.title) + \'&amp;token=' . $token . '\';var u = f + p;var a = function () {if (!w.open(u))l.href = u;};if (/Firefox/.test(navigator.userAgent))setTimeout(a, 0); else a();void(0);})()';

?>
    <a class="forkit" href="https://github.com/azlux/Simple-URL-Shortener/">
        <span>Fork me on GitHub!</span>
        <span>Get free cookie!</span>
    </a>
    <script>
        async function checkCommentAvailable(e){
            const {value} = e.target
            if(value === ""){
                return disableCommentField(false)
            }
            const result = await fetch(`/shorten.php?url=${value}`)
            if(!result.ok){
                return disableCommentField(true)
            }
            try{
                const body = await result.json()
                console.log(body)
            }
            catch(e){
                console.error(e)
                return disableCommentField(true)
            }
        }

        function disableCommentField(isDisabled){
            const i = document.querySelector('input[name=comment]')
            i.setCustomValidity(isDisabled ? "This comment allready exists." : '')
        }
    </script>
    <div id="content">
        <form name="url_form" action="shorten.php" method="post">
            <div class="form-group">
                <label class="form-label" for="shorten_form_url">Link to shorten</label>
                <input class="form-input" type="text" id="shorten_form_url" name="url"/>
            </div>
            <div class="flex flex-space">
                <div class="form-group">
                    <label class="form-label" for="shorten_form_custom">Optional short url</label>
                    <input class="form-input" type="text" id="shorten_form_custom" name="custom" maxlength="30">
                </div>
                <div class="form-group">
                    <label class="form-label" for="shorten_form_comment">Optional comment</label>
                    <input class="form-input" type="text" id="shorten_form_comment" name="comment" onkeypress="checkCommentAvailable(event)" maxlength="30">
                </div>
                </div>
            <div>
                <input type="submit" value="Shorten" class="btn btn-primary"/>
                <a href="/list.php" class="float-right btn">List of shortened links</a>
            </div>
        </form>
        <div class="flex">
            <div id="credits" class="flex-grow flex-center">
                <a href="https://github.com/azlux/Simple-URL-Shortener/" class="text-center">
                    Shortener by Azlux
                </a>
            </div>
	    <a href="<?php echo $code_js ?>" class="btn tooltip" data-tooltip="You can add this link as bookmark
(click and drop into your bookmark toolbar).
After that, you can click to short the current url!" onclick="event.preventDefault();"/>Bookmark</a>
        </div>
    </div>
    <script>
        document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', closeAllModals))
    </script>
</body>
</html>
