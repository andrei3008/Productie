 $(document).tooltip({
     content: function () {
         var element = $(this);
         return element.attr("title");
      }
  })
 

$(document).ready(function() {
    // main.show_macuri();
    // setTimeout(main.show_macuri(), 6000);
});

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
	show_locatii: function(pers, op) {
      main.start_loading('#locatiiPanel');
        $.ajax({
            type: "POST",
            url: 'ajax/main_locatii.php',
            data: {
                'idresp': pers,
                'operator': op
            },
            success: function (result) {
                main.stop_loading('#locatiiPanel');
                $('#locatiiPanel').html(result);
                first_loc = $("#tabs-1 ul li.list-group-item.first").find('.getLocatie2').attr('data-locatie');
                main.show_aparate(first_loc, op);
                $(document).on('click', '.getLocatie2', function (event) {
                    event.preventDefault();
                    var idLoc = $(this).attr('data-locatie');
                    main.show_aparate(idLoc, op);
                });
            }
        });
	},
    show_aparate: function(idLoc, operator) { 
        var id_pers = $('#id_pers').val();
        main.start_loading('#locatieTarget');
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
                // butoane.get_tabel_sas();
            }
        });
    },
    start_loading: function(dom) {
        $(dom).append('<div class="loading"><img src="css/AjaxLoader.gif" /></div>');
        $('.loading').show();
    },
    stop_loading: function(dom) {
        $(dom).find('.loading').remove();
    },
}
/***********************************************************************
    RELOAD LISTA LOCATII PENTRU RESPONSABIL
***********************************************************************/
    // $(document).on('click', '.ampera-tr2', function (e) {
    //     e.preventDefault();
    //     var pers = $(this).attr('data-pers');
    //     var op = $(this).attr('data-op');
    //     $('#locatiiPanel').load("ajax/main_locatii.php", { "idresp": pers, "operator": op});
    // });
    // $(document).on('click', '.redlong-tr', function (e) {
    //     e.preventDefault();
    //     var pers = $(this).attr('data-pers');
    //     var op = $(this).attr('data-op');
    //     $.ajax({
    //         url: DOMAIN + "/router.php",
    //         type: "POST",
    //         data: {
    //             "idresp": pers,
    //             "operator": op
    //         },
    //         success: function (response) {
    //             location.reload();
    //         }
    //     });
    // });
/***********************************************************************
    END RELOAD LISTA LOCATII PENTRU RESPONSABIL
***********************************************************************/
$(document).ready(function() {
    // main.show_locatii(1, 1);

    $(document).on('click', '.ampera-tr2, .redlong-tr2, .total-tr2', function (event) {
        event.preventDefault();
        var pers = $(this).attr('data-pers');
        var op = $(this).attr('data-op');
	    main.show_locatii(pers, op);
        
    })
    
})

var getUrlParameter = function getUrlParameter(sParam) {
                var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
                }
            };
            $(document).on('click', '.audit', function (event) {
                event.preventDefault();
                var ip = $(this).attr('data-ip');
                var port = $(this).attr('data-port');
                var serie = $(this).attr('data-seria');
                var id = $(this).attr('data-id');
                var user = $('#idUser').val();

                $.ajax({
                    type: "POST",
                    url: 'ajax/testPic.php',
                    data: {
                        'ip': ip,
                        'port': port,
                        'seria': serie,
                        'id': id,
                        'user': user
                    },
                    success: function (result) {
                        alert(result);
                        window.location.reload();
                    }
                });
            });
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
            $(function () {
                window.setInterval("$('.blink').toggle();", 500);
                // $('#locatiiPanel').load("ajax/main_locatii.php", { });
            });
            //                            $('.blocat').click(function (event) {
            //                                event.stopImmediatePropagation();
            //                            });
			
$('document').ready(function(){
	var locatie2 = $(".btn-warning").attr("data-idloc");
	if ( locatie2 == 792) {
		$(".blocat").removeClass("blocat");
	}
});			