function createID() {
    var id = (((1+Math.random())*0x10000)|0).toString(16).substring(1);
    var today = new Date(), expires = new Date();
    expires.setTime(today.getTime() + (360*24*60*60*1000));
    document.cookie = "id =" + encodeURIComponent(id) + ";expires=" + expires.toGMTString();
}

function getID() {
    var oRegex = new RegExp("(?:; )?" + "id" + "=([^;]*);?");

    if (oRegex.test(document.cookie)) {
        return decodeURIComponent(RegExp["$1"]);
    } else {
        createID();
        getID();
    }
}
document.getElementById("userID").setAttribute('href',"list.php?userID="+getID());
document.getElementById("userID2").value = getID();