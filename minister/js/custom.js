$(document).ready(function ($) {
    var height = $('.container-fluid').height();
    $('.list-group').height((height - 150) + 'px');
    $('.list-group').css('overflow', 'scroll');
    $('.panel-default').css('position', 'fixed');
    $('.panel-default').css('width', '23%');


    $('a.ajax').click(function (e) {
        e.preventDefault();
        var offset = $(this).attr('data-offset');
        var index = $(this).attr('data-index');
        $.ajax({
            url: 'getLocation.php',
            method: 'POST',
            data: {'offset': offset, 'index': index},
            success: function (html) {
                $('#main-right').html(html);
            }
        });
    });
    $('a.ajax').click(function (e) {
        $('.targeted').removeClass('targeted');
        $(this).addClass('targeted');
    });
    $('#box').instaFilta();
    $(window).bind("beforeunload", function () {

        $.ajax({
            type: "POST",
            url: 'ajax/windowClose.php',
            data: {},
            success: function (result) {
            },
            error: function (result) {
            }
        });
    });
    $(window).bind("onbeforeunload", function () {

        $.ajax({
            type: "POST",
            url: 'ajax/windowClose.php',
            data: {},
            success: function (result) {
            },
            error: function (result) {
            }
        });
    });

    $('#butonCautare').click(function (event) {
        event.preventDefault();
        var serieCautata = $('#seria_cautata').val().toUpperCase();
        if (isNaN(serieCautata)) {
            if (serieCautata.indexOf(' ') === -1) {
                var output = serieCautata.substr(0, 2) + ' ' + serieCautata.substr(2);
                $(window).scrollTop($(".panel:contains(" + output + "):last").offset().top);
                var tableRow = $("td").filter(function () {
                    return $(this).text() == output;
                }).closest("tr");
                tableRow.addClass('red');
            } else {
                $(window).scrollTop($(".panel:contains(" + serieCautata + "):last").offset().top);
                var tableRow = $("td").filter(function () {
                    return $(this).text() == serieCautata;
                }).closest("tr");
                tableRow.addClass('red');
            }
        } else {
            $(window).scrollTop($(".panel:contains(" + serieCautata + "):last").offset().top);
            var tableRow = $("td").filter(function () {
                return $(this).text() == serieCautata;
            }).closest("tr");
            tableRow.addClass('red');
        }
    });
});
$(document).ready(function () {
    var nr_locatii = $('#nr_locatii').attr('data-max');
    $('#locatii_disp').html(' (' + nr_locatii + ' locatii)');
});
window.onbeforeunload = function () {
    var http = new XMLHttpRequest();
    var url = "ajax/windowClose.php";
    var params = "lorem=ipsum&name=binny";
    http.open("POST", url, true);
    http.send();
}