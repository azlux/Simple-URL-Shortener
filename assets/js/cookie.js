function bookmark() {
    document.getElementById("bookmark").setAttribute('href', "javascript:(function () {var d = document;var w = window;var enc = encodeURIComponent;var f = '" + window.location.href + "index.php';var l = d.location;var p = '?shorten=' + enc(l.href) + '&comment=' + enc(d.title) + '&userID="+getID()+"';var u = f + p;var a = function () {if (!w.open(u))l.href = u;};if (/Firefox/.test(navigator.userAgent))setTimeout(a, 0); else a();void(0);})();");
}
bookmark();
