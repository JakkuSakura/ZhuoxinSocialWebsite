var trytimes_ad = 500;
function clearAD() {
    if (window.location.host !== "zhuoxinsocial.top")
        return;
    if (trytimes_ad-- > 0)
    {
        var bn = $("#vdbanner");
        if (bn.length)
        {
            bn.attr("style", "display:none");
            trytimes_ad = 0;
        }
        else
        {
            setTimeout("clearAD()", 200);
        }
    }
}
clearAD();