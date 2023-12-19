<?php
include("inc/bdd.php");

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
if(!empty($_SESSION['username'])){
    $username = $_SESSION['username'];
    $token = $_SESSION['token'];
}
if (PUBLIC_INSTANCE == 'true' and empty($username)){
    $username = 'UNKNOWN';
    $token = '';
}
if (PUBLIC_INSTANCE != 'true' and empty($username)) {
    $token = '';
}

include 'inc/header.php';

// Main page
$code_js = 'javascript:(function () {var d = document;var w = window;var enc = encodeURIComponent;var f =\' ' . DEFAULT_URL . '\';var l = d.location;var p = \'shorten.php?url=\' + enc(l.href) + \'&amp;comment=\' + enc(d.title) + \'&amp;token=' . $token . '\';var u = f + p;var a = function () {if (!w.open(u))l.href = u;};if (/Firefox/.test(navigator.userAgent))setTimeout(a, 0); else a();void(0);})()';

?>
    <a class="forkit" href="https://github.com/azlux/Simple-URL-Shortener/">
        <span>Fork me on GitHub!</span>
        <span>Get free cookie!</span>
    </a>
    <script>
        async function checkCustomUrlAvailable(e){
            const {value} = e.target
            if(value === ""){
                return disableCustomUrlField(false)
            }
            const result = await fetch('shorten.php', {method: "POST", headers: {"Content-Type": "application/x-www-form-urlencoded"}, body: `is_short_free=${value}`})
            if(!result.ok){
                return disableCustomUrlField(true)
            }
            try{
                const {ok} = await result.json()
                disableCustomUrlField(!ok)
            }
            catch(e){
                console.error(e)
                return disableCustomUrlField(true)
            }
        }

        function disableCustomUrlField(isDisabled){
            const i = document.querySelector('input[name=custom]')
            i.setCustomValidity(isDisabled ? "This custom url allready exists." : '')
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
                    <input class="form-input" type="text" id="shorten_form_custom" name="custom" onkeyup="checkCustomUrlAvailable(event)" maxlength="30">
                </div>
                <div class="form-group">
                    <label class="form-label" for="shorten_form_comment">Optional comment</label>
                    <input class="form-input" type="text" id="shorten_form_comment" name="comment" maxlength="30">
                </div>
                </div>
            <div>
                <input type="submit" value="Shorten" class="btn btn-primary"/>
                <button class="btn float-right" onclick="event.preventDefault(); window.location.href = 'list.php'">List of shortened links</button>
            </div>
        </form>
        <div class="flex">
	    <div id="credits" class="flex-grow flex-center">
                <a href="https://github.com/azlux/Simple-URL-Shortener/" class="text-center">
                    Simple-URL-Shortener Project
                </a>
            </div>
            <a href="<?php echo $code_js ?>" class="btn tooltip" data-tooltip="You can add this link as bookmark
(click and drop into your bookmark toolbar).
After that, you can click to short the current url!" onclick="event.preventDefault();"/>Bookmark</a>
        </div>
    </div>
<?php include 'inc/footer.php';?>
