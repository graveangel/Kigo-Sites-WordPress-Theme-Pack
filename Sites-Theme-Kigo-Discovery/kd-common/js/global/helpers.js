/**
 * Sets a cookie
 * @param {[string]} cname  [cookie name]
 * @param {[string]} cvalue [the cookie value]
 * @param {[integer]} exdays [number of days to expire]
 */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
/**
 * gets a cookie
 * @param  {[string]} cname [the cookie name]
 * @return {[string]}       [the cookie value]
 */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}
