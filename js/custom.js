const DOMAIN = "http://red77.ro";

function doNothing(event) {
    event.preventDefault();
}
/**
 *
 * @returns {int|jQuery}
 */
function getLuna() {
    return $("#luna").val();
}
/**\
 *
 * @returns {int|jQuery}
 */
function getAn() {
    return $("#an").val();
}

function changeZi(buton) {
    doNothing(event);
    var an = getAn();
    var luna = getLuna();
    var zi = $(buton).attr("data-zi");
    $.ajax({
        url: DOMAIN + "/router.php",
        type: "POST",
        data: {
            "zi": zi,
            "luna": luna,
            "an": an
        },
        success: function (response) {
            location.reload();
        }
    });
}
function changeResponsabil(obj){
    var idresp = obj.getAttribute("data-id");
    var idop = obj.getAttribute("data-op");
    if(idop == 0){
        idop = null;
    }
    $.ajax({
        url : "router.php",
        type : "POST",
        data : {
            "idresp" : idresp,
            "operator" : idop
        },
        success : function(result){
            location.reload();

            //console.log(result);
        }
    });

}

function changeLocatie(obj){
    var idLocatie = obj.getAttribute("data-idLocatie");
    $.ajax({
        url : "router.php",
        type : "POST",
        data : {
            'idLocatie' : idLocatie
        },
        success : function() {
            location.reload();
        }

    });
}

function schimbaLuna(element) {
    var luna = $(element).val();
    $.ajax({
        url: DOMAIN + "/router.php",
        type: "POST",
        data: {
            "luna": luna
        },
        success: function (response) {
            location.reload();
        }
    });
}

function schimbaAn(element) {
    var an = $(element).val();
    $.ajax({
        url: DOMAIN + "/router.php",
        type: "POST",
        data: {
            'an': an
        },
        success: function (response) {
            location.reload();
        }
    });
}

function schimbaResponsabil(element) {
    var resp = $(element).val();
    $.ajax({
        url: DOMAIN + "/router.php",
        type: "POST",
        data: {
            "idresp": resp
        },
        success: function (response) {
            //alert(response);
            location.reload();
        }
    });
}

function changeZona(element) {
    var zona = $(element).val();
    $.ajax({
        url: DOMAIN + "/router.php",
        type: "POST",
        data : {
            "zona" : zona
        },
        success : function(response){
            //alert(response);
            location.reload()
        }
    });
}

function mergiLaLocatie(element,event)
{
    event.preventDefault();
    var idresp = $(element).attr("data-idResp");
    var idLocatie = $(element).attr("data-idLocatie");
    var idOperator = $(element).attr("data-idOperator");
    $.ajax({
        url : DOMAIN + "/router.php",
        type : "POST",
        data : {
            'operator' : idOperator,
            'idresp' : idresp,
            'idLocatie' : idLocatie
        },
        success : function(response){
            location.href = DOMAIN + "/main.php";
        }
    })
}
$(document).ready(function () {
    var zoom = document.documentElement.clientWidth / window.innerWidth;
    $(window).resize(function () {
        var zoomNew = document.documentElement.clientWidth / window.innerWidth;
        if (zoom != zoomNew) {
            $('.navbar').css({'height': "59px"})
            zoom = zoomNew
        }
    });
    var inaltimeCorecta = $('body').outerHeight() - $('#tabeleResponsabili').parent().height() - 130;
    var inaltimeMain = $('#mainPanel').outerHeight() - 73;
    var inaltimeLocatii = $('#locatiiPanel').outerHeight();
    if ($(window).width() < 1000) {
        $('.height130').css({'padding': '0px', 'width': '33%'});
    }
    $('#mainPanel .body').height(inaltimeCorecta);
    $('#mainPanel .body').css({'overflow': 'auto'});
    $('#locatiiPanel .panel-body').height(inaltimeCorecta - 59);
    $('#locatiiPanel .panel-body').css({'overflow': 'auto'});
    $('.black').change(function (e) {
        e.preventDefault();
        $('#select-responsabil').submit();
    });
    $(document).on('click', '.initiaza', function () {
        var info = $(this).attr("data-info");
        var nume = $(this).attr('data-nume');
        $.ajax({
            type: "POST",
            url: 'set.php',
            data: {'info': info, 'nume': nume},
            success: function (event) {
                location.reload();
            }
        });
    });
    $(document).on('change', '#an', function () {
        var an = $(this).val();
        var luna = $('#lunaCurenta').val();
        var loc = $('#loc').val();
        $.ajax({
            type: "POST",
            url: 'getZile.php',
            data: {'luna': luna, 'an': an, 'loc': loc},
            success: function (result) {
                $('#zile').html(result);
            }
        });
    });
    $(document).on('change', '#luna', function () {
        var an = $('#anulCurent').val();
        var luna = $(this).val();
        var loc = $('#loc').val();
        $.ajax({
            type: "POST",
            url: 'getZile.php',
            data: {'luna': luna, 'an': an, 'loc': loc},
            success: function (result) {
                $('#zile').html(result);
            }
        });
    });
    $('.disabled').on('click', '.disabled', function (e) {
        e.preventDefault();
    });
    $('.extra').popover();
    $('.btn').popover();

    $('.extra').on('click', function (e) {
        e.preventDefault();
        $('.extra').not(this).popover('hide');
    });
    $('body').on('change', '.uploadMetro', function (e) {
        e.preventDefault();
        var form = $(this).parent();
        form.submit();
    });
    $(document).on('click', '.ampera-tr', function (e) {
        e.preventDefault();
        var pers = $(this).attr('data-pers');
        var op = $(this).attr('data-op');
        $.ajax({
            url: DOMAIN + "/router.php",
            type: "POST",
            data: {
                "idresp": pers,
                "operator": op
            },
            success : function (response){
                location.reload();
            }
        });
    });
    $(document).on('click', '.redlong-tr', function (e) {
        e.preventDefault();
        var pers = $(this).attr('data-pers');
        var op = $(this).attr('data-op');
        $.ajax({
            url: DOMAIN + "/router.php",
            type: "POST",
            data: {
                "idresp": pers,
                "operator": op
            },
            success: function (response) {
                location.reload();
            }
        });
    });
    $(document).on('click', '.total-tr', function (e) {
        e.preventDefault();
        var pers = $(this).attr('data-pers');
        $.ajax({
            url: DOMAIN + "/router.php",
            type: "POST",
            data: {
                "idresp": pers,
                "operator": null
            },
            success: function (response) {
                location.reload();
            }
        });
    });
    $(document).on('click', '#save-form-submit', function (e) {
        e.preventDefault();
        $('#submit-me').click();
    });
    $("#datepicker").datepicker({
        dateFormat: "yy-mm-dd"
    });

    /* var ajax_call = function () {
     var id = $('#salveazaParola').attr('data-user');
     $.ajax({
     type: "POST",
     url: 'getUserStatus.php',
     data: {'id': id},
     success: function (result) {
     alert(result);
     }
     });
     };

     var interval = 1000 * 60 * 1; // where X is your every X minutes

     setInterval(ajax_call, interval); */
    $(document).on('change', '#lunaLog', function (e) {
        var luna = $(this).val();
        var an = $('#anCurentLog').val();
        e.preventDefault();
        window.location.href = '?luna=' + luna;
    });
    $(document).on('change', '#anLog', function (e) {
        e.preventDefault();
        var an = $(this).val();
        var luna = $("#lunaCurentLog").val();
        window.location.href = '?luna=' + luna + '&an=' + an;
    });

    $(document).on('click', '#schimbaParola', function (e) {
        e.preventDefault();
        var loc = $("#val_loc").val();
        var pass = $("#form-pass").val();
        $.ajax({
            type: "POST",
            url: 'ajax/modify.php',
            data: {'pass': pass, 'loc': loc},
            success: function (result) {
                BootstrapDialog.show({
                    title: "Informatie!",
                    message: result,
                    animate: false,
                    buttons: [{
                        label: 'Inchide',
                        action: function (dialogRef) {
                            dialogRef.close();
                        }
                    }]
                });
            }
        });
    });
    $('#inventar').click(function (event) {
        event.preventDefault();
        $('#inventar-modal').modal();
    });
    $('#salveazaInventar').click(function () {
        var nume = $('#denProdInv').val();
        var cantitate = $('#canProdInv').val();
        var stare = $('#stareProdInv').val();
        var observatii = $('#observatiiProdInv').val();
        var idLocatie = $('#idLocProdInv').attr('placeholder');
        $.ajax({
            type: "POST",
            url: 'ajax/saveInventar.php',
            data: {
                'nume': nume,
                'cantitate': cantitate,
                'stare': stare,
                'observatii': observatii,
                'idLocatie': idLocatie
            },
            success: function (result) {
                var continut = $('.modal-body').html();
                $('.modal-body').html(result);
                setTimeout(
                    function () {
                        $('.modal-body').html(continut);
                    }, 5000);
            }
        });
    });
    $(document).on('click', '#panel-angajati', function (e) {
        var panel = $(this).parent();
        panel.find('.panel-body').toggle(1000);
    });
    $(document).on('click', '#infoFirma', function (e) {
        var panel = $(this).parent();
        panel.find('.panel-body').toggle(1000);
    });
    $(document).on('click', '#inventarPanel', function (e) {
        var panel = $(this).parent();
        panel.find('.panel-body').toggle(1000);
    });
    $(document).on('change', '.angajati', function (e) {
        var ids = $(this).val();
        var telefon = $(this).attr('data-telefon');
        iduri = ids.split('_');
        idVechi = iduri[0];
        idNou = iduri[1];
        $.ajax({
            type: "POST",
            url: 'ajax/personal.php',
            data: {
                'idVechi': idVechi,
                'telefon': telefon,
                'idNou': idNou,
            },
            success: function (result) {
                $('#panel-angajati').append(result);
                setTimeout(
                    function () {
                        $('#dispare').hide();
                    }, 5000);
            }

        });
    });
    $(document).on('click', '#adaugaPersonal', function (event) {
        event.preventDefault();
        $('#addPersonal').modal();
    });
    $(document).on('click', '#salveazaPersonal', function (event) {
        event.preventDefault();
        var nume = $('#numePersonal').val();
        var prenume = $('#prenumePersonal').val();
        var telefon = $('#telefonPersonal').val();
        var email = $('#emailPersonal').val();
        var idLocatie = $('#idLocProdInv').attr('placeholder');
        var nick = $('#nickPersoana').val();
        if (telefon === '') {
            alert('Va rugam sa introduceti un numar de telefon!');
        } else if (telefon !== '') {
            $.ajax({
                type: "POST",
                url: 'ajax/personalSave.php',
                data: {
                    'nume': nume,
                    'prenume': prenume,
                    'telefon': telefon,
                    'email': email,
                    'idLocatie': idLocatie,
                    'nick': nick
                },
                success: function (result) {
                    alert(result);
                    $('#numePersonal').val('');
                    $('#prenumePersonal').val('');
                    $('#telefonPersonal').val('');
                    $('#emailPersonal').val('');
                    $('#nickPersoana').val('');
                }
            });
        }
    });

    $('.inchidePanel').click(function (event) {
        event.preventDefault();
        $('#iframeMetrologii').hide();
        $('#tabeleResponsabili').show();
    });
    $('#changeZoom').click(function (event) {
        event.preventDefault();
        if ($('.table-over').length) {
            $(this).text('Activeaza Zoom');
            $('.zoomable').removeClass('table-over');
        } else {
            $(this).text('Dezactiveaza Zoom');
            $('.zoomable').addClass('table-over');
        }
    });
});
$(document).on('click', '.rowAparat', function (event) {
    event.preventDefault();
    $(this).next().next().toggle(1000);
});
$(document).on('click', '.metrologii', function (event) {
    event.preventDefault();
    var metrologie = $(this).attr('href');
    var height = $('#tabeleResponsabili').parent().height();
    $('#iframeMetrologii iframe').attr('src', metrologie);
    $('#iframeMetrologii iframe').css({'height': height});
    $('#tabeleResponsabili').hide();
    $('#iframeMetrologii').show();
});
$(document).on('click', '.configurare', function (event) {
    event.preventDefault();
    var configurare = $(this).attr('href');
    var height = $('#tabeleResponsabili').parent().height();
    $('#iframeMetrologii iframe').attr('src', configurare);
    $('#iframeMetrologii iframe').css({'height': height});
    $('#tabeleResponsabili').hide();
    $('#iframeMetrologii').show();
    var frame = document.getElementById('iframeMetrologii');
    frame.onload = function () {
        var body = frame.contentWindow.document.querySelector('#Tbl_02');
        body.style.fontSize = '12px';
    };
});
$(document).on('click', '.autorizatii', function (event) {
    event.preventDefault();
    var autorizatie = $(this).attr('href');
    var height = $('#tabeleResponsabili').parent().height();
    $('#iframeMetrologii iframe').attr('src', autorizatie);
    $('#iframeMetrologii iframe').css({'height': height});
    $('#tabeleResponsabili').hide();
    $('#iframeMetrologii').show();
})
/*************************************************************************** 
    BUTOANE
***************************************************************************/
var butoane = {
    get_tabel_sas: function() {
        dom = '#locatieTarget';
        main.start_loading(dom);
        var idLoc = $('#tabel-sas').attr('data-idloc'); 
        var idOperator = $('#tabel-sas').attr('data-op'); 
        var idResp = $('#tabel-sas').attr('data-resp'); 
        $.ajax({
            type: "POST",
            url: DOMAIN + '/ajax/main-aparate-sas.php',
            data: {
                'idLoc': idLoc,
                'idOperator': idOperator,
                'idResp': idResp
            },
            success: function (result) {
                $(dom).html(result);
                main.stop_loading(dom);
            }
        });
    },
    get_istoric_aparat:function(obj) {
        $("#modal-idAparat").html(obj.idAparat);
        $("#modal-seriaAparat").html(obj.seriaAparat);
        $('#modal-istoric').modal();
        $("#modal-istoric-body").html("Se incarca istoric ... ");
        if ($(window).width() > 1200) {
            $('#modal-istoric .modal-dialog').css({width: '1200px'});
        }
        
        $.ajax({
            type: "POST",
            url: DOMAIN + '/ajax/main-aparate-istoric.php',
            data: {
                'idAparat': obj.idAparat,
                'seriaAparat': obj.seriaAparat
            },
            success: function (result) {
                $("#modal-istoric-body").html(result);
            }
        });
    },
    show_modal_configurare: function(dom) {
        $('#modals').modal();
        $('#modals .modal-dialog').css({width: '99%', height: (0.85* screen.height)+'px'});
        $('#modals .modal-dialog  .modal-content').css({width: '100%', height: '100%'});
        var serie= $(dom).attr("data-serie");
        var an = $(dom).attr("data-an");
        var luna = $(dom).attr("data-luna");
        var content_h = 0.70* screen.height;
        $('#modals #modal-title').html('Configurare aparat seria <strong>'+serie+'</strong>');
        $("#modals .modal-content .row").html('<iframe src="interfataPic/game.php?seria='+serie+'&an='+an+'&luna='+luna+'" style="width: 100%;height: '+content_h+'px"></iframe>');
    }
}
$(document).ready(function() {
    $(document).on('click', '#tabel-sas', function (event) {
        event.preventDefault();
        butoane.get_tabel_sas();
    })
    $(document).on('click', '#tabel-man', function (event) {
        event.preventDefault();
        var idLoc = $('#tabel-man').attr('data-idloc');
        main.show_aparate(idLoc);
    })
    $(document).on('click', '.istoricAparate', function (event) {
        event.preventDefault();
        var idAparat= $(this).attr("data-id");
        var seriaAparat= $(this).attr("data-seria");
        butoane.get_istoric_aparat({idAparat:idAparat, seriaAparat:seriaAparat})     
    });
    $(document).on('click', '.configurare', function (event) {
        event.preventDefault();
        // butoane.show_modal_configurare(this)     
    });
    (function check_luna() {
        $.ajax({
            url: DOMAIN + '/ajax/verifica-luna.php',
            success: function (result) {
                // Schedule the next request when the current one's complete
                if (result.timp >= '3600') {
                    $.ajax({
                        url: DOMAIN + "/router.php",
                        type: "POST",
                        data: {
                            "luna": result.luna,
                            "an": result.an
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });
                } else {
                    setTimeout(check_luna, 10000);
                }
            },
            complete: function () {
                
                
            }
        });
    })();
    (function macurile() {
        $.ajax({
            url: 'ajax/macuri.php',
            success: function (data) {
                $('#macuri').html(data);
                // (macurile(), 10000);
            },
            complete: function () {
                setTimeout(macurile, 10000);
                
            }
        });
    })();

})
