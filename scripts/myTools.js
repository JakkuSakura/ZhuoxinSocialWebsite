// 将form转为AJAX提交
function ajaxSubmit(frm, fn) {
    var dataPara = getFormJson(frm);
    $.ajax({
        url: frm.action,
        type: frm.method,
        data: dataPara,
        dataType: "text",
        async: true,
        success: fn
    });
}

// 将form中的值转换为键值对。
function getFormJson(frm) {
    var o = {};
    var a = $(frm).serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
}

(function () {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?cca0204f9b0bb67010112d2470424070";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

function myalert(msg, title) {
    title = title || "提示";
    $("#message").html(msg);
    $("#header").html(title);
    $('.small.modal')
        .modal('show')
    ;
}