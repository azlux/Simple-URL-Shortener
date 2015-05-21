function createID() {
    var id = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var today = new Date(), expires = new Date();
    expires.setTime(today.getTime() + (360 * 24 * 60 * 60 * 1000));
    document.cookie = "id =" + encodeURIComponent(id) + ";expires=" + expires.toGMTString();
}

function getID() {
    var oRegex = new RegExp("(?:; )?" + "id" + "=([^;]*);?");

    if (oRegex.test(document.cookie)) {
        return decodeURIComponent(RegExp["$1"]);
    } else {
        createID();
        return getID();
    }
}
function bookmark() {
    document.getElementById("bookmark").setAttribute('href', "javascript:(function () {var d = document;var w = window;var enc = encodeURIComponent;var f = '"+window.location.href+"index.php';var l = d.location;var p = '?shorten=' + enc(l.href) + '&comment=' + enc(d.title) + '&userID="+getID()+"';var u = f + p;var a = function () {if (!w.open(u))l.href = u;};if (/Firefox/.test(navigator.userAgent))setTimeout(a, 0); else a();void(0);})();");
}
document.getElementById("userID").setAttribute('href', "list.php?userID=" + getID());
document.getElementById("userID2").value = getID();
bookmark();
