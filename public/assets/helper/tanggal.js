function format_dateTime(dateTime, timezone_offset_minutes) {
    
    var tanggal = new Date(dateTime);
    tanggal.setMinutes(tanggal.getMinutes() + timezone_offset_minutes);
    var dd = tanggal.getDate();
    var mm = tanggal.getMonth()+1; //January is 0!
    var yyyy = tanggal.getFullYear();
    var hh = tanggal.getHours();
    var ii = tanggal.getMinutes();
    var ss = tanggal.getSeconds();

    dd = (dd<10) ? '0'+dd : dd;
    mm = (mm<10) ? '0'+mm : mm;
    hh = (hh<10) ? '0'+hh : hh;
    ii = (ii<10) ? '0'+ii : ii;
    ss = (ss<10) ? '0'+ss : ss;
    
    return yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + ii + ':' + ss;
}

function datebox_tanggal(element) {
    $(element).datebox.defaults.formatter = function (date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
    };

    $(element).datebox.defaults.parser = function (s) {
        if (!s)
            return new Date();
        var ss = (s.split('/'));
        var y = parseInt(ss[2], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[0], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    };

    $(element).datebox().datebox('textbox').inputmask("dd/mm/yyyy");
    
}

function format_tanggal() {
    $.fn.datebox.defaults.formatter = function (date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
    };

    $.fn.datebox.defaults.parser = function (s) {
        if (!s)
            return new Date();
        var ss = (s.split('/'));
        var y = parseInt(ss[2], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[0], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    };
}

function myformatter(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
}

function myparser(s) {
    if (!s)
        return new Date();
    var ss = (s.split('/'));
    var y = parseInt(ss[2], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[0], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        return new Date(y, m - 1, d);
    } else {
        return new Date();
    }
}

function encode_tanggal(value) {
    var values = value.split("/");
    var keys = ["day", "month", "year"];
    var final = {};
    for (var i = 0; i < values.length; i++) {
        final[keys[i]] = values[i];
    }
    var new_date = final.year + "-" + final.month + "-" + final.day;
    return new_date;
}

function decode_tanggal(value) {
    if (value === "0000-00-00 00:00:00" || value === "0000-00-00") {
        return "";
    } else {
        var tgl = new Date(value);
        var y = tgl.getFullYear();
        var m = tgl.getMonth() + 1;
        var d = tgl.getDate();
        return (d < 10 ? '0' + d : d) + '/' + (m < 10 ? '0' + m : m) + '/' + y;
    }
}

function tanggal_awal() {
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m, 1);
    return decode_tanggal(firstDay);
}

function tanggal_akhir() {
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var lastDay = new Date(y, m + 1, 0);
    return decode_tanggal(lastDay);
}

function tahun(element) {
    $(element).textbox().textbox('textbox').inputmask("y", {
        alias: "date",
        placeholder: "yyyy",
        yearrange: {minyear: 1900, maxyear: (new Date()).getFullYear()}
    });
}