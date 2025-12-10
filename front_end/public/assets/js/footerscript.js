function TOAST_NOTIFICATION(ALERT_TYPE, MESSAGE, TITLE, SHOW_DURATION, HIDE_DURATION, TIMEOUT, EXTENTED_TIME_OUT, SHOW_EASING, HIDE_EASING, SHOW_METHOD, HIDE_METHOD, ALERT_POSITION) {
    var t = ALERT_TYPE,
        e = "rtl" === $("html").attr("dir"),
        o = MESSAGE,
        s = TITLE,
        a = SHOW_DURATION || '300',
        n = HIDE_DURATION || '1000',
        i = TIMEOUT || '5000',
        r = EXTENTED_TIME_OUT || '1000',
        l = SHOW_EASING || 'swing',
        c = HIDE_EASING || 'linear',
        p = SHOW_METHOD || 'fadeIn',
        u = HIDE_METHOD || 'fadeOut',
        h = $("#addClear").prop("checked"),
        m = void 0 === toastr.options.positionClass ? "toast-top-center" : toastr.options.positionClass,
        o = (toastr.options = {
            maxOpened: 1,
            autoDismiss: !0,
            closeButton: true,
            debug: false,
            newestOnTop: false,
            progressBar: false,
            positionClass: ALERT_POSITION || "toast-top-center",
            preventDuplicates: true,
            onclick: null,
            rtl: e
        }, o || (m = ["Sorry !!! Unable to Complete the action"])[y = ++y === m.length ? 0 : y]),
        v = toastr[t](o, s);
}