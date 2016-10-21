 $(document).tooltip({
     content: function () {
         var element = $(this);
         return element.attr("title");
      }
  })
 
var main = {
    show_macuri: function() {
        $.ajax({
            url: 'ajax/macuri.php',
            success: function (data) {
                $('#macuri').html(data);
            },
            complete: function () {
                // Schedule the next request when the current one's complete
                // setTimeout(main.show_macuri(), 60000);
            }
        });
    },
    ajust_height_locatiiPanel: function() {
        setTimeout(function(){
            var inaltimeCorecta = $('#locatieTarget').height();
            var inaltimeCorecta_head = $('#locatiiPanel_header').height() + 20;
            var inaltimeCorecta_fara_head = inaltimeCorecta - inaltimeCorecta_head - 25;
            // console.log(inaltimeCorecta + '- ' + inaltimeCorecta_head);
            $('#locatiiPanel_locatii').height(inaltimeCorecta_fara_head);
            $('#locatiiPanel_locatii').css({'overflow': 'auto'});
        }, 4000);
    },
    /******************************************************************************
     * [show_locatii_total_header description]
     * @param  {[int]}      pers [id responsabil]
     * @param  {[int]}      op   [id operator]
     * @param  {[string]}   sort [ASC / DESC]
     * @param  {[string]}   type [sortare dupa ex: culoareAparat - rosu/verde]
    ******************************************************************************/
        show_locatii_total_header: function(pers, op, sort, type) {
            main.start_loading('#locatiiPanel_locatii');
            $.ajax({
                type: "POST",
                url: 'ajax/main_locatii_total_header.php',
                data: {
                    'idresp': pers,
                    'operator': op,
                    'sort': sort,
                    'type': type,
                },
                success: function (result) {
                    // main.stop_loading('#locatiiPanel_header');
                    $('#locatiiPanel_header').html(result);
                    main.show_locatii_total(pers, op, 'DESC', 'culoareAparat');
                    $(document).on('click', '.locatii-sort', function (event) {
                        event.preventDefault();
                        $(this).off('click');
                        var resp = $(this).attr('data-resp');
                        var operator = $(this).attr('data-operator');
                        var sort = $(this).attr('data-sort');
                        var type = $(this).attr('data-type');
                        main.show_locatii_total(resp, operator, sort, type);

                    });
                }
            });
        },
    	show_locatii_total: function(pers, op, sort, type) {
          // main.start_loading('#locatiiPanel_locatii');
          // console.log(pers + ' - ' + op + ' - ' + sort + ' - ' + type);
            $.ajax({
                type: "POST",
                url: 'ajax/main_locatii_total.php',
                data: {
                    'idresp': pers,
                    'sort': sort,
                    'type': type,
                    'operator': op                
                },
                success: function (result) {
                    main.stop_loading('#locatiiPanel_locatii');
                    $('#locatiiPanel_locatii').html(result);
                    $('#locatieAuto').instaFilta();
                    $('#locatiiPanel_locatii').height(600);
                    $('#locatiiPanel_locatii').css({'overflow': 'auto'});

                    first_loc = $("#tabs-1 ul li.list-group-item.first").find('.getLocatie2').attr('data-locatie');

                    main.show_aparate(first_loc, op, 1);
                    main.ajust_height_locatiiPanel();
                
                    $(document).on('click', '.getLocatie2', function (event) {
                        event.preventDefault();
                        var idLoc = $(this).attr('data-locatie');
                        var op = $(this).attr('data-op');
                        $('.getLocatie2').each(function() {
                            $(this).parent().removeClass('activated');
                        })
                        $(this).parent().addClass('activated');
                        main.ajust_height_locatiiPanel();
                        main.show_aparate(idLoc, op, 1);
                    });
                    
                }
            });
    	},
    /****************************************************************************** 
    *   END show_locatii_total_header
    ******************************************************************************/
    /******************************************************************************
     * [show_aparate LISTARE APARATE DREAPTA]
     * @param  {[int]}      idLoc           [id LOCATIE pe care se face click]
     * @param  {[int]}      operator        [id operator]
     * @param  {[int]}      show_loading    [1 / 0]
    ******************************************************************************/
        show_aparate: function(idLoc, operator, show_loading) { 
            var id_pers = $('#id_pers').val();
            if (show_loading == 1) {
                main.start_loading('#locatieTarget');
            }
            $.ajax({
                type: "POST",
                url: 'ajax/main_aparate.php',
                data: {
                    'idLocatie': idLoc,
                    'idresp': id_pers,
                    'operator': operator
                },
                success: function (result) {
                    main.stop_loading('#locatieTarget');

                    $("#locatieTarget").html(result);
                    console.log('dadadada');
                    
                }
            });
        },
    /****************************************************************************** 
    *   END show_aparate
    ******************************************************************************/
    /******************************************************************************
     * [set_aparat_pozitie UPDATE POZITIE APARAT IN LOCATIE]
     * @param  {[int]}      idAparat        [id APARAT]
     * @param  {[int]}      pozitieNoua     [noua pozitie]
    ******************************************************************************/
        set_aparat_pozitie: function(idAparat, pozitieNoua) {
            $.ajax({
                type: "POST",
                url: 'ajax/aparate.php',
                data: {
                    'idAparat': idAparat,
                    'pozitieNoua': pozitieNoua,
                    'type': 'updatePozitie'
                },
                success: function (result) {
                    alert(result.mesaj);
                }
            });
        },
    /****************************************************************************** 
    *   END set_aparat_pozitie
    ******************************************************************************/
        start_loading: function(dom) {
            $(dom).append('<div class="loading"><img src="css/AjaxLoader.gif" /></div>');
            $('.loading').show();
        },
        stop_loading: function(dom) {
            $(dom).find('.loading').remove();
        },
}

$(document).ready(function() {
    /*----------------------------------------------------------
    *    VARIANTA NOUA
    *    LISTARE APARATE DREAPTA - RULEAZA LA FIECARE 10secunde
    ----------------------------------------------------------*/
        (function aparatili() {
            // var idOp = $('#aparate_idOp').val();
            // var idlocatie = $('#aparate_idlocatie').val();
            // var idpers = $('#aparate_idpers').val();
            var idOp = $('.activated a.getLocatie2').attr('data-op');
            var idlocatie = $('.activated a.getLocatie2').attr('data-locatie');
            var idpers = $('.activated a.getLocatie2').attr('data-pers');
            main.show_aparate(idlocatie, idOp, 0);
            setTimeout(aparatili, 10000);
        })();
    /**----------------------------------------------------------
     *   click pe locatii responsabil - A, R, T
     *   listare locatii stanga
    -----------------------------------------------------------*/
        $(document).on('click', '.total-tr2, .ampera-tr2, .redlong-tr2', function (event) {
            event.preventDefault();
            var pers = $(this).attr('data-pers');
            var op = $(this).attr('data-op');
            main.show_locatii_total_header(pers, op, 'DESC', 'culoareAparat');       
            // main.show_locatii_total(pers, op, 'DESC', 'culoareAparat');   
        })
    /**----------------------------------------------------------
     *   click pe locatii responsabil - A, R, T
     *   listare locatii stanga
    -----------------------------------------------------------*/
        $(document).on('change', '.aparat-pozitie', function (event) {
            event.preventDefault();
            main.set_aparat_pozitie($(this).attr('data-idAparat'), $(this).val());
        })
    /**----------------------------------------------------------
     *   refresh locatie
    -----------------------------------------------------------*/
        $(document).on('click', '#refreshLocatie', function (event) {
            event.preventDefault();
            var idLoc = $(this).attr('data-locatie');
            var op = $(this).attr('data-op');
            main.show_aparate(idLoc, op, 1);
        })

    var locatie2 = $(".btn-warning").attr("data-idloc");
    if ( locatie2 == 792) {
        $(".blocat").removeClass("blocat");
    }
    $(document).on('click', '.ipPic', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var url = $(this).attr('href');
        var height = $('#tabeleResponsabili').parent().height();
        $('#iframeMetrologii iframe').attr('src', url);
        $('#iframeMetrologii iframe').css({'height': height});
        $('#tabeleResponsabili').hide();
        $('#iframeMetrologii').show();
    });
    $(document).on('click', '.noClick', function (event) {
        event.preventDefault();
        event.stopPropagation();
    });
    $(document).on('click', '.techPic', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        var url = $(this).attr('href');
        var height = $('#tabeleResponsabili').parent().height();
        $('#iframeMetrologii iframe').attr('src', url);
        $('#iframeMetrologii iframe').css({'height': height});
        $('#tabeleResponsabili').hide();
        $('#iframeMetrologii').show();
    });

    $(document).on('click', '.eroriPic', function (event) {
        event.stopImmediatePropagation();
        var idAparat = $(this).attr('data-idAparat');
        var serieAparat = $(this).attr('serie-aparat');
        var macAparat = $(this).attr('mac-aparat');
        var url = 'http://86.122.183.194/pic/index.php?idAparat=' + idAparat +'&serieAparat=' + encodeURIComponent(serieAparat) +'&macAparat=' + encodeURIComponent(macAparat);
        var height = $('#tabeleResponsabili').parent().height();
        $('#iframeMetrologii iframe').attr('src', url);
        $('#iframeMetrologii iframe').css({'height': height});
        $('#tabeleResponsabili').hide();
        $('#iframeMetrologii').show();
    });
    /*----------------------------------------------------------
        VARIANTA VECHE LISTARE APARATE DREAPTA
    ----------------------------------------------------------*/
        $(document).on('click', '.getLocatie', function (event) {
            event.preventDefault();

            var idLoc = $(this).attr('data-locatie');
            $.ajax({
                type: "POST",
                url: DOMAIN + '/router.php',
                data: {
                    'idLocatie': idLoc
                },
                success: function (result) {
                    location.reload();
                }
            });
        });
    $(document).on("click", ".eroriTotale", function (event) {
        event.stopImmediatePropagation();
        var id = $(this).attr('data-id');
        location.href = "<?php echo DOMAIN ;?>/rapoarte/erori.php?id=" + id;
    })
})


$(function () {
    window.setInterval("$('.blink').toggle();", 500);
});	