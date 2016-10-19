$(document).ready(function ($) {
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
    if ($(window).width() > 1200) {
        var height = $('.container-fluid').height();
        $('.list-group').height((height - 150) + 'px');
        $('.list-group').css('overflow', 'scroll');
        $('.panel-default').css('position', 'fixed');
        $('.panel-default').css('width', '23%');
    } else {
        $('#nav').css('display', 'none');
        $('#box').mouseenter(function () {
            $('#nav').toggle("slow");
        });
        $('#box').mouseleave(function () {
            $('#nav').toggle("slow");
        });
        $('.panel-body').css('padding', 0);
        $('.container-fluid').css('padding', 0);
        $('.col-md-9').css('padding', 0);
    }
    $('a.ajax').click(function(event){
        event.preventDefault();
        var id = $(this).attr('data-index');
        $(window).scrollTop($("#"+id).offset().top);
    });
    $('a.ajax').click(function (e) {
        $('.targeted').removeClass('targeted');
        $(this).addClass('targeted');
    });
    $('#box').instaFilta();

    $('#butonCautare').click(function(event){
        event.preventDefault();
        var serieCautata = $('#seria_cautata').val().toUpperCase();
        if($.isNumeric(serieCautata))
        {
            $(window).scrollTop($(".panel:contains("+serieCautata+"):last").offset().top);
        }else{
            if(serieCautata.indexOf(' ') != -1){
                $(window).scrollTop($(".panel:contains("+serieCautata+"):last").offset().top);
            }else{
                var position = 2;
                var a = serieCautata;
                var b = ' ';
                var output = [a.slice(0, position), b, a.slice(position)].join('');
                $(window).scrollTop($(".panel:contains("+output+"):last").offset().top);
            }
    }

    });
});
$(document).ready(function () {
    var nr_locatii = $('#nr_locatii').attr('data-max');
    $('#locatii_disp').html(' (' + nr_locatii + ' locatii)');
});
